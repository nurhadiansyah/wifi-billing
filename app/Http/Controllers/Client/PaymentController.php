<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // =================================================================
    // 1. TAMPILKAN HALAMAN PILIH METODE PEMBAYARAN (CHECKOUT VIEW)
    // =================================================================
    public function checkout($id)
    {
        // Cari data tagihan (Di blade Anda menggunakan nama variabel $bill)
        $bill = Invoice::where('id', $id)
                          ->where('user_id', auth()->user()->id)
                          ->firstOrFail();

        // Jika sudah punya link pembayaran, lewati halaman ini dan langsung ke Tripay
        if ($bill->checkout_url) {
            return redirect($bill->checkout_url);
        }

        // Ambil daftar metode pembayaran (Channels) dari Tripay
        $apiKey = env('TRIPAY_API_KEY');
        $mode = env('TRIPAY_MODE', 'sandbox');
        $baseUrl = $mode === 'production' ? 'https://tripay.co.id/api/' : 'https://tripay.co.id/api-sandbox/';
        
        // Memanggil API Tripay khusus untuk melihat daftar metode pembayaran
        $response = Http::withToken($apiKey)->get($baseUrl . 'merchant/payment-channel');
        $channels = $response->json()['data'] ?? [];

        // Tampilkan halaman Blade Anda (Sesuaikan folder view-nya jika berbeda)
        // Misalnya view-nya ada di resources/views/client/payment/checkout.blade.php
        return view('client.payment.checkout', compact('bill', 'channels'));
    }

    // =================================================================
    // 2. PROSES PEMBUATAN TRANSAKSI KE TRIPAY SAAT TOMBOL DIKLIK
    // =================================================================
    public function store(Request $request, $id)
    {
        // Validasi pastikan pelanggan sudah memilih metode pembayaran
        $request->validate([
            'method' => 'required|string'
        ]);

        $invoice = Invoice::where('id', $id)
                          ->where('user_id', auth()->user()->id)
                          ->where('status', 'unpaid')
                          ->firstOrFail();

        // Ambil Kunci Rahasia
        $apiKey       = env('TRIPAY_API_KEY');
        $privateKey   = env('TRIPAY_PRIVATE_KEY');
        $merchantCode = env('TRIPAY_MERCHANT_CODE');
        $merchantRef  = $invoice->invoice_number; 
        $amount       = $invoice->amount;

        // Buat Signature
        $signature = hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privateKey);
        $user = auth()->user();

        // Siapkan Data ke Tripay
        $data = [
            'method'         => $request->method, // MENGAMBIL DARI PILIHAN FORM BLADE ANDA
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '081234567890',
            'order_items'    => [
                [
                    'name'     => 'Tagihan Internet',
                    'price'    => $amount,
                    'quantity' => 1,
                ]
            ],
            'return_url'     => route('client.tagihan'),
            'callback_url'   => url('/tripay/callback'),
            'expired_time'   => (time() + (24 * 60 * 60)), 
            'signature'      => $signature
        ];

        // Tembak API Tripay Create Transaction!
        $response = Http::withToken($apiKey)->post(env('TRIPAY_URL'), $data);
        $result = $response->json();

        if ($response->successful() && isset($result['success']) && $result['success'] == true) {
            // Simpan link dan referensi ke database
            $invoice->update([
                'tripay_reference' => $result['data']['reference'],
                'checkout_url'     => $result['data']['checkout_url'],
            ]);

            // Arahkan ke link pembayaran Tripay
            return redirect($result['data']['checkout_url']);
        } else {
            $errorMessage = $result['message'] ?? 'Terjadi kesalahan saat menghubungi server Tripay.';
            return back()->with('error', 'Gagal membuat pembayaran: ' . $errorMessage);
        }
    }

    // =================================================================
    // 3. MELIHAT INSTRUKSI PEMBAYARAN YANG SUDAH DIBUAT
    // =================================================================
    public function detail($id)
    {
        $invoice = Invoice::where('id', $id)
                          ->where('user_id', auth()->user()->id)
                          ->firstOrFail();
        
        if ($invoice->checkout_url) {
            return redirect($invoice->checkout_url);
        }

        return redirect()->route('client.payment.checkout', $id);
    }

    // =================================================================
    // 4. CALLBACK TRIPAY (WEBHOOK)
    // =================================================================
    public function callback(Request $request)
    {
        $privateKey = env('TRIPAY_PRIVATE_KEY');
        
        $callbackSignature = $request->header('x-callback-signature');
        $json = $request->getContent();
        
        \Log::info('Tripay Callback Triggered', [
            'signature_header' => $callbackSignature,
            'event' => $request->header('x-callback-event'),
            'payload' => $json
        ]);

        $signature = hash_hmac('sha256', $json, $privateKey);
        
        if ($signature !== $callbackSignature) {
            \Log::error('Tripay Signature Mismatch', [
                'expected' => $signature,
                'received' => $callbackSignature,
                'private_key_used' => $privateKey
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 400);
        }

        if ('payment_status' !== $request->header('x-callback-event')) {
            \Log::warning('Tripay Unrecognized Event', ['event' => $request->header('x-callback-event')]);
            return response()->json(['success' => false, 'message' => 'Unrecognized event, ignored'], 200);
        }

        $data = json_decode($json);

        if (is_array($data) || is_object($data)) {
            $merchantRef = $data->merchant_ref;
            $tripayReference = $data->reference;
            $status = strtoupper((string) $data->status);

            \Log::info('Tripay Callback Decoded', [
                'merchantRef' => $merchantRef,
                'tripayReference' => $tripayReference,
                'status' => $status
            ]);

            if ($status === 'PAID') {
                $invoice = Invoice::where('invoice_number', $merchantRef)
                                ->where('tripay_reference', $tripayReference)
                                ->first();

                if ($invoice) {
                    if ($invoice->status === 'paid') {
                        \Log::info('Tripay Invoice already paid', ['invoice_number' => $merchantRef]);
                        return response()->json(['success' => true, 'message' => 'Already paid']);
                    }

                    $invoice->update([
                        'status' => 'paid',
                        'payment_method' => $data->payment_method ?? 'Tripay Payment'
                    ]);
                    
                    \Log::info('Tripay Invoice updated successfully', ['invoice_number' => $merchantRef]);
                    return response()->json(['success' => true]);
                }
                
                \Log::error('Tripay Invoice not found', ['merchantRef' => $merchantRef, 'tripayReference' => $tripayReference]);
                return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
            }
            
            return response()->json(['success' => true, 'message' => 'Status is not PAID, ignored']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid data format'], 400);
    }
}