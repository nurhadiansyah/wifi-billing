<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Invoice; 
use Carbon\Carbon;
use Illuminate\Support\Str; // Tambahan untuk membuat nomor invoice acak

class GenerateTagihanOtomatis extends Command
{
    protected $signature = 'tagihan:generate';
    protected $description = 'Menerbitkan tagihan otomatis H-3 sebelum tanggal jatuh tempo pelanggan';

    public function handle()
    {
        // 1. Menentukan Tanggal Jatuh Tempo (H-3 dari hari ini)
        $tanggalJatuhTempo = Carbon::now()->addDays(3);
        
        // Mengambil angka tanggalnya saja (misal: 8) untuk mencari di tabel users
        $targetTanggal = (int) $tanggalJatuhTempo->format('j'); 
        
        // Format tanggal lengkap (YYYY-MM-DD) untuk disimpan ke kolom due_date
        $dueDateString = $tanggalJatuhTempo->format('Y-m-d');
        
        // Ambil bulan dan tahun untuk pengecekan agar tidak dobel tagihan
        $bulanIni = $tanggalJatuhTempo->format('m');
        $tahunIni = $tanggalJatuhTempo->format('Y');

        // 2. Cari pelanggan yang jatuh temponya 3 hari lagi dan punya paket
        $pelanggan = User::where('status', 'aktif')
                        ->where('tanggal_tagihan', $targetTanggal)
                        ->whereNotNull('package_id') 
                        ->with('package') 
                        ->get();

        $jumlahDiterbitkan = 0;

        foreach ($pelanggan as $user) {
            
            // 3. Cek apakah invoice bulan & tahun ini sudah dibuat (Berdasarkan due_date)
            $cekTagihan = Invoice::where('user_id', $user->id)
                                 ->whereMonth('due_date', $bulanIni)
                                 ->whereYear('due_date', $tahunIni)
                                 ->first();

            if (!$cekTagihan) {
                if($user->package) {
                    
                    // Buat Nomor Invoice Otomatis (Contoh: INV-20260808-2-ABCD)
                    // (Format: INV - TahunBulanTanggal - IDUser - 4HurufAcak)
                    $invoiceNumber = 'INV-' . $tanggalJatuhTempo->format('Ymd') . '-' . $user->id . '-' . strtoupper(Str::random(4));

                    // 4. Masukkan ke database sesuai struktur tabel gambar Anda!
                    Invoice::create([
                        'user_id'        => $user->id,
                        'invoice_number' => $invoiceNumber, // Kolom dari gambar
                        
                        // CATATAN: Pastikan 'harga' sesuai dengan nama kolom di tabel packages Anda. 
                        // Jika di tabel packages pakainya bahasa inggris, ubah jadi $user->package->price
                        'amount'         => $user->package->price, // Kolom dari gambar
                        
                        'due_date'       => $dueDateString, // Kolom dari gambar
                        'status'         => 'unpaid', // Kolom dari gambar
                    ]);
                    
                    $jumlahDiterbitkan++;
                }
            }
        }

        $this->info("Berhasil menerbitkan {$jumlahDiterbitkan} tagihan baru. (Jatuh Tempo: {$dueDateString})");
    }
}