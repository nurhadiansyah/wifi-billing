<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Package;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    // Menampilkan daftar tagihan
    public function index(Request $request)
    {
        $query = Invoice::with('user')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(10);
        $customers = User::where('role', 'client')->get();
        $packages = Package::all();
        
        return view('admin.invoices.index', compact('invoices', 'customers', 'packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'due_date' => 'required|date',
        ]);

        $user = User::with('package')->findOrFail($request->user_id);
        
        if (!$user->package) {
            return redirect()->back()->with('error', 'Pelanggan belum memiliki paket WiFi.');
        }

        $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        Invoice::create([
            'user_id' => $request->user_id,
            'invoice_number' => $invoiceNumber,
            'amount' => $user->package->price,
            'due_date' => $request->due_date,
            'status' => 'unpaid',
        ]);

        return redirect()->route('admin.tagihan.index')->with('success', 'Tagihan berhasil dibuat!');
    }

    // Memperbarui tagihan (Edit)
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,paid',
        ]);

        $invoice = Invoice::findOrFail($id);
        
        $invoice->update([
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.tagihan.index')->with('success', 'Data tagihan berhasil diperbarui!');
    }

    // FITUR CERDAS: Generate Tagihan Massal Berdasarkan Paket Masing-Masing Pelanggan
    public function generateBulk(Request $request)
    {
        $request->validate([
            'due_date' => 'required|date',
        ]);

        // Ambil semua pelanggan (role: client) yang memiliki paket terikat
        $clients = User::where('role', 'client')->whereNotNull('package_id')->with('package')->get();

        if ($clients->isEmpty()) {
            return redirect()->route('admin.tagihan.index')->with('error', 'Tidak ada pelanggan yang memiliki paket WiFi. Pastikan pelanggan sudah diatur paketnya.');
        }

        $count = 0;
        foreach ($clients as $client) {
            // Ambil harga asli dari paket pelanggan tersebut
            $amount = $client->package->price;

            // Buat nomor tagihan unik
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . $client->id . '-' . rand(100, 999);

            Invoice::create([
                'user_id' => $client->id,
                'invoice_number' => $invoiceNumber,
                'amount' => $amount, // Nominal otomatis sesuai paket mereka!
                'due_date' => $request->due_date,
                'status' => 'unpaid',
            ]);
            $count++;
        }

        return redirect()->route('admin.tagihan.index')->with('success', "Berhasil membuat $count tagihan otomatis sesuai paket masing-masing pelanggan!");
    }

    // Mengubah status tagihan menjadi Lunas (Paid)
    public function markAsPaid($id)
    {
        // Cari data tagihan berdasarkan ID
        $invoice = \App\Models\Invoice::findOrFail($id);

        // Ubah statusnya menjadi 'paid' (sesuai aturan database)
        $invoice->update([
            'status' => 'paid', 
            'payment_method' => 'Manual (Cash/Transfer Langsung)',
        ]);

        // FITUR BARU: Sesuaikan siklus tanggal tagihan dengan tanggal pembayaran hari ini
        if ($invoice->user) {
            $todayDay = \Carbon\Carbon::today()->day;
            $invoice->user->update([
                'tanggal_tagihan' => $todayDay
            ]);
        }

        // Kembalikan ke halaman daftar tagihan dengan pesan sukses
        return redirect()->route('admin.tagihan.index')->with('success', 'Status tagihan Lunas, dan Siklus Tagihan pelanggan diperbarui ke tanggal ' . $todayDay . ' setiap bulannya!');
    }

    // Menghapus tagihan
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('admin.tagihan.index')->with('success', 'Tagihan berhasil dihapus!');
    }

    private function sendFonnteMessage($target, $message)
    {
        $setting = \App\Models\Setting::first();
        $token = $setting ? $setting->fonnte_token : env('FONNTE_TOKEN');
        if (empty($token)) {
            return false;
        }

        // Format target ke format internasional (misal: +62 atau 62 jika diperlukan oleh Fonnte)
        // Kita ubah awalan 0 menjadi 62 jika ada
        if (substr($target, 0, 1) === '0') {
            $target = '62' . substr($target, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
                'delay' => '2', // Jeda 2 detik antar pesan (mencegah banned/spam)
            ]);
            
            // Log response API ke dalam storage/logs/laravel.log
            \Illuminate\Support\Facades\Log::info('Fonnte Response: ' . $response->body());

            return $response->successful();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendWaReminder(Request $request, $id)
    {
        $invoice = Invoice::with('user')->findOrFail($id);

        if ($invoice->status == 'paid') {
            return redirect()->back()->with('error', 'Tagihan sudah lunas, tidak perlu dikirim pengingat.');
        }

        $phone = $invoice->user->phone;
        if (!$phone) {
            return redirect()->back()->with('error', 'Pelanggan tidak memiliki nomor telepon (WhatsApp).');
        }

        $amount = number_format($invoice->amount, 0, ',', '.');
        $dueDateObj = \Carbon\Carbon::parse($invoice->due_date);
        $dueDateStr = $dueDateObj->format('d/m/Y');
        $periodEndStr = $dueDateObj->copy()->addMonth()->format('d/m/Y');
        $packageName = $invoice->user->package ? $invoice->user->package->name : 'Paket Internet';

        $message = "Hai Pelanggan Setia Dreamnet\n\n"
                 . "Yth. Bapak/Ibu {$invoice->user->name}\n\n"
                 . "Terima kasih atas kesetiaan & kepercayaan Anda memilih layanan Dreamnet, Kami informasikan Invoice anda telah terbit dan dapat dibayarkan, berikut rinciannya :\n"
                 . "ID Pelanggan: {$invoice->user->phone}\n"
                 . "Nomor Invoice: {$invoice->invoice_number}\n"
                 . "Amount: Rp {$amount}\n"
                 . "PPN: 0\n"
                 . "Discount: 0\n"
                 . "Total: Rp {$amount}\n"
                 . "Item: Internet {$invoice->user->name} - {$packageName}\n"
                 . "Jatuh tempo: {$dueDateStr}\n"
                 . "Period: {$dueDateStr} - {$periodEndStr}\n"
                 . "Mohon segera lakukan pembayaran sebelum jatuh tempo\n\n"
                 . "Terima kasih.\n\n"
                 . "silahkan melakukan pembayaran di website resmi kami 🙏🏻 di dreamnetindonesia\n\n\n"
                 . "Pihak Dreamnet Tidak menerima Pembayaran secara Tunai melalui Marketing,Teknisi  Atupun Agen Lainya  yang \n"
                 . "mengatasnamakan Dreamnet\n\n"
                 . "Ini adalah pesan otomatis - mohon untuk tidak membalas langsung ke pesan ini";

        $success = $this->sendFonnteMessage($phone, $message);

        if ($success) {
            return redirect()->back()->with('success', 'Pesan pengingat WhatsApp berhasil dikirim ke ' . $invoice->user->name);
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim pesan WhatsApp. Pastikan Token Fonnte disetel di .env atau nomor tujuan benar.');
        }
    }

    public function sendBulkWaReminder(Request $request)
    {
        $invoices = Invoice::with('user')->where('status', 'unpaid')->get();

        if ($invoices->isEmpty()) {
            return redirect()->back()->with('info', 'Tidak ada tagihan yang belum lunas.');
        }

        $count = 0;
        foreach ($invoices as $invoice) {
            $phone = $invoice->user->phone;
            if ($phone) {
                $amount = number_format($invoice->amount, 0, ',', '.');
                $dueDateObj = \Carbon\Carbon::parse($invoice->due_date);
                $dueDateStr = $dueDateObj->format('d/m/Y');
                $periodEndStr = $dueDateObj->copy()->addMonth()->format('d/m/Y');
                $packageName = $invoice->user->package ? $invoice->user->package->name : 'Paket Internet';

                $message = "Hai Pelanggan Setia Dreamnet\n\n"
                         . "Yth. Bapak/Ibu {$invoice->user->name}\n\n"
                         . "Terima kasih atas kesetiaan & kepercayaan Anda memilih layanan Dreamnet, Kami informasikan Invoice anda telah terbit dan dapat dibayarkan, berikut rinciannya :\n"
                         . "ID Pelanggan: {$invoice->user->phone}\n"
                         . "Nomor Invoice: {$invoice->invoice_number}\n"
                         . "Amount: Rp {$amount}\n"
                         . "PPN: 0\n"
                         . "Discount: 0\n"
                         . "Total: Rp {$amount}\n"
                         . "Item: Internet {$invoice->user->name} - {$packageName}\n"
                         . "Jatuh tempo: {$dueDateStr}\n"
                         . "Period: {$dueDateStr} - {$periodEndStr}\n"
                         . "Mohon segera lakukan pembayaran sebelum jatuh tempo\n\n"
                         . "Terima kasih.\n\n"
                         . "silahkan melakukan pembayaran di website resmi kami 🙏🏻 di dreamnetindonesia\n\n\n"
                         . "Pihak Dreamnet Tidak menerima Pembayaran secara Tunai melalui Marketing,Teknisi  Atupun Agen Lainya  yang \n"
                         . "mengatasnamakan Dreamnet\n\n"
                         . "Ini adalah pesan otomatis - mohon untuk tidak membalas langsung ke pesan ini";

                $apiSuccess = $this->sendFonnteMessage($phone, $message);
                if ($apiSuccess) {
                    $count++;
                }
            }
        }

        if ($count > 0) {
            return redirect()->back()->with('success', "Berhasil memproses pengiriman WhatsApp ke $count pelanggan.");
        } else {
            return redirect()->back()->with('error', "Gagal mengirim pesan massal. Pastikan Token Fonnte di .env valid dan aktif.");
        }
    }
}