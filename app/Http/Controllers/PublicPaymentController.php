<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class PublicPaymentController extends Controller
{
    // =================================================================
    // 0. CEK TAGIHAN DARI LANDING PAGE
    // =================================================================
    public function checkBill(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ]);

        $identifier = $request->identifier;

        $user = User::with(['invoices' => function($query) {
                        $query->orderBy('due_date', 'desc');
                    }])
                    ->where(function($query) use ($identifier) {
                        $query->where('email', $identifier)
                              ->orWhere('phone', $identifier);
                    })
                    ->first();

        if (!$user) {
            return redirect()->to(url('/#section-cek-tagihan'))->with('error', 'Data tidak ditemukan. Pastikan Email atau Nomor Telepon sesuai dengan yang didaftarkan.');
        }

        return view('public.payment.check', compact('user'));
    }

    // =================================================================
    // 1. TAMPILKAN HALAMAN PILIH METODE PEMBAYARAN PUBLIK
    // =================================================================
    public function checkout($invoice_number)
    {
        // Cari data tagihan berdasarkan nomor invoice
        $bill = Invoice::with('user')->where('invoice_number', $invoice_number)->firstOrFail();

        // Jika sudah punya link pembayaran, lewati halaman ini dan langsung ke Tripay
        if ($bill->checkout_url) {
            return redirect($bill->checkout_url);
        }

        // Ambil daftar metode pembayaran (Channels) dari Tripay
        $apiKey = env('TRIPAY_API_KEY');
        $mode = env('TRIPAY_MODE', 'sandbox');
        $baseUrl = $mode === 'production' ? 'https://tripay.co.id/api/' : 'https://tripay.co.id/api-sandbox/';
        
        $response = Http::withToken($apiKey)->get($baseUrl . 'merchant/payment-channel');
        $channels = $response->json()['data'] ?? [];

        return view('public.payment.checkout', compact('bill', 'channels'));
    }

    // =================================================================
    // 2. PROSES PEMBUATAN TRANSAKSI KE TRIPAY
    // =================================================================
    public function store(Request $request, $invoice_number)
    {
        $request->validate([
            'method' => 'required|string'
        ]);

        $invoice = Invoice::with('user')->where('invoice_number', $invoice_number)
                          ->where('status', 'unpaid')
                          ->firstOrFail();

        $apiKey       = env('TRIPAY_API_KEY');
        $privateKey   = env('TRIPAY_PRIVATE_KEY');
        $merchantCode = env('TRIPAY_MERCHANT_CODE');
        $merchantRef  = $invoice->invoice_number; 
        $amount       = $invoice->amount;

        $signature = hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privateKey);
        
        // Data pelanggan (Bisa jadi user sudah dihapus, pakai fallback)
        $customerName = $invoice->user ? $invoice->user->name : 'Pelanggan';
        $customerEmail = $invoice->user ? $invoice->user->email : 'email@domain.com';
        $customerPhone = $invoice->user && $invoice->user->phone ? $invoice->user->phone : '081234567890';

        $data = [
            'method'         => $request->method,
            'merchant_ref'   => $merchantRef,
            'amount'         => $amount,
            'customer_name'  => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'order_items'    => [
                [
                    'name'     => 'Tagihan Internet',
                    'price'    => $amount,
                    'quantity' => 1,
                ]
            ],
            // Arahkan kembali ke halaman ini agar bisa lihat instruksi Tripay jika perlu
            'return_url'     => route('public.payment.checkout', $invoice->invoice_number),
            'callback_url'   => url('/tripay/callback'),
            'expired_time'   => (time() + (24 * 60 * 60)), 
            'signature'      => $signature
        ];

        $mode = env('TRIPAY_MODE', 'sandbox');
        $baseUrl = $mode === 'production' ? 'https://tripay.co.id/api/' : 'https://tripay.co.id/api-sandbox/';
        
        $response = Http::withToken($apiKey)->post($baseUrl . 'transaction/create', $data);
        $result = $response->json();

        if ($response->successful() && isset($result['success']) && $result['success'] == true) {
            $invoice->update([
                'tripay_reference' => $result['data']['reference'],
                'checkout_url'     => $result['data']['checkout_url'],
            ]);

            return redirect($result['data']['checkout_url']);
        } else {
            $errorMessage = $result['message'] ?? 'Terjadi kesalahan saat menghubungi server Tripay.';
            return back()->with('error', 'Gagal membuat pembayaran: ' . $errorMessage);
        }
    }
}
