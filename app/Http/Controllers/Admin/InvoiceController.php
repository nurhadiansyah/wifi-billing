<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Package;

class InvoiceController extends Controller
{
    // Menampilkan daftar tagihan
    public function index()
    {
        $invoices = Invoice::with('user')->latest()->get();
        $customers = User::where('role', 'client')->get();
        $packages = Package::all();
        
        return view('admin.invoices.index', compact('invoices', 'customers', 'packages'));
    }

    // Menyimpan tagihan manual (satuan)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'due_date' => 'required|date',
        ]);

        $package = Package::findOrFail($request->package_id);
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);

        Invoice::create([
            'user_id' => $request->user_id,
            'invoice_number' => $invoiceNumber,
            'amount' => $package->price,
            'due_date' => $request->due_date,
            'status' => 'unpaid',
        ]);

        return redirect()->route('admin.tagihan.index')->with('success', 'Tagihan berhasil dibuat!');
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

        // Kembalikan ke halaman daftar tagihan dengan pesan sukses
        return redirect()->route('admin.tagihan.index')->with('success', 'Status tagihan berhasil diubah menjadi Lunas secara manual!');
    }

    // Menghapus tagihan
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('admin.tagihan.index')->with('success', 'Tagihan berhasil dihapus!');
    }
}