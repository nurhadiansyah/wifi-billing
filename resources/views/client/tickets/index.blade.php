@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu Utama /</span> Pusat Bantuan</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="mb-0">Riwayat Laporan Anda</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBuatLaporan">
            <i class='bx bx-plus-circle me-1'></i> Buat Laporan Baru
        </button>
    </div>
    
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Judul Laporan / Keluhan</th>
                    <th>Status</th>
                    <th>Aksi</th> <!-- Kolom Baru -->
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td>
                            <strong>{{ $ticket->subject }}</strong><br>
                            <small class="text-muted text-wrap" style="max-width: 300px; display: inline-block;">
                                {{ Str::limit($ticket->description, 60) }}
                            </small>
                        </td>
                        <td>
                            @if($ticket->status == 'pending')
                                <span class="badge bg-label-warning"><i class="bx bx-time-five"></i> Menunggu Respon</span>
                            @elseif($ticket->status == 'proses')
                                <span class="badge bg-label-info"><i class="bx bx-cog bx-spin"></i> Sedang Diproses</span>
                            @elseif($ticket->status == 'selesai')
                                <span class="badge bg-label-success"><i class="bx bx-check-double"></i> Selesai</span>
                            @endif
                        </td>
                        <td>
                            <!-- TOMBOL HUBUNGI ADMIN WA -->
                            @php
                                // GANTI NOMOR INI DENGAN NOMOR WA ADMIN (Gunakan 62 di depannya, tanpa angka 0)
                                $no_wa_admin = '6285242899941'; 
                                
                                // Mengambil alamat (jika kosong, tampilkan teks alternatif)
                                $alamat = auth()->user()->address ?? 'Belum ada data alamat';

                                // Teks otomatis yang menyertakan Nama, Alamat, dan Detail Keluhan
                                $pesan_wa = "Halo Admin, saya pelanggan atas nama *" . auth()->user()->name . "*. Saya ingin menanyakan kelanjutan laporan gangguan saya dengan detail berikut:\n\n*Alamat Lokasi:* " . $alamat . "\n*Subjek:* " . $ticket->subject . "\n*Detail Keluhan:* " . $ticket->description . "\n\nMohon bantuannya untuk segera dicek. Terima kasih.";
                            @endphp
                            
                            <a href="https://wa.me/{{ $no_wa_admin }}?text={{ urlencode($pesan_wa) }}" target="_blank" class="btn btn-sm btn-success">
                                <i class='bx bxl-whatsapp fs-5 me-1'></i> Hubungi Admin
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada laporan keluhan yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Buat Laporan Baru -->
<div class="modal fade" id="modalBuatLaporan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Laporan Gangguan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('client.bantuan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        Beritahu kami jika Anda mengalami kendala pada koneksi internet Anda. Tim teknisi kami akan segera menanganinya.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subjek Laporan</label>
                        <select name="subject" class="form-select" required>
                            <option value="">-- Pilih Kendala --</option>
                            <option value="Internet Mati Total (LOS Merah)">Internet Mati Total (LOS Merah)</option>
                            <option value="Koneksi Lemot / Ping Tinggi">Koneksi Lemot / Ping Tinggi</option>
                            <option value="Lampu Router Mati">Lampu Router Mati</option>
                            <option value="Ganti Password WiFi">Permintaan Ganti Password WiFi</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Detail Kendala</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detail masalah yang Anda alami secara singkat..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection