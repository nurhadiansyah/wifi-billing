@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen Sistem /</span> Data Laporan Pelanggan</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <h5 class="card-header">Daftar Tiket Pengaduan</h5>
    
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Detail Kendala</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td>
                            <strong>{{ $ticket->user->name }}</strong><br>
                            <small class="text-muted">{{ $ticket->user->phone ?? 'Tidak ada No HP' }}</small>
                        </td>
                        <td>
                            <strong>{{ $ticket->subject }}</strong><br>
                            <small class="text-muted text-wrap" style="max-width: 250px; display: inline-block;">
                                {{ Str::limit($ticket->description, 50) }}
                            </small>
                        </td>
                        <td>
                            @if($ticket->status == 'pending')
                                <span class="badge bg-label-warning"><i class="bx bx-time-five"></i> Pending</span>
                            @elseif($ticket->status == 'proses')
                                <span class="badge bg-label-info"><i class="bx bx-cog bx-spin"></i> Proses</span>
                            @elseif($ticket->status == 'selesai')
                                <span class="badge bg-label-success"><i class="bx bx-check-double"></i> Selesai</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-2">
                                <!-- Tombol Ubah Status -->
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalUpdateStatus{{ $ticket->id }}">
                                    <i class="bx bx-edit-alt me-1"></i> Ubah Status
                                </button>

                                <!-- DROPDOWN / PILIHAN KIRIM KE TEKNISI -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-success dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class='bx bxl-whatsapp me-1'></i> Kirim ke Teknisi
                                    </button>
                                    <ul class="dropdown-menu">
                                        @php
                                            $alamat_pelanggan = $ticket->user->address ?? 'Belum ada alamat';
                                            $hp_pelanggan = $ticket->user->phone ?? 'Tidak ada No HP';
                                            
                                            $pesan_teknisi = "Halo Tim Teknisi, mohon segera meluncur untuk mengecek gangguan pelanggan berikut:\n\n"
                                                           . "*Nama Pelanggan:* " . $ticket->user->name . "\n"
                                                           . "*No. HP:* " . $hp_pelanggan . "\n"
                                                           . "*Alamat:* " . $alamat_pelanggan . "\n"
                                                           . "*Keluhan:* " . $ticket->subject . "\n"
                                                           . "*Detail:* " . $ticket->description . "\n\n"
                                                           . "Mohon kabari jika sudah di lokasi.";
                                        @endphp

                                        @forelse($technicians as $tech)
                                            <li>
                                                <a class="dropdown-item" href="https://wa.me/{{ $tech->phone }}?text={{ urlencode($pesan_teknisi) }}" target="_blank">
                                                    <i class='bx bx-user-check me-1 text-success'></i> {{ $tech->name }} {{ $tech->area }}
                                                </a>
                                            </li>
                                        @empty
                                            <li><span class="dropdown-item text-muted">Belum ada teknisi</span></li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>

                            <!-- Modal Update Status (Tetap Sama) -->
                            <div class="modal fade" id="modalUpdateStatus{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Perbarui Status Laporan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.laporan.update', $ticket->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <p>Pelanggan: <strong>{{ $ticket->user->name }}</strong></p>
                                                <p class="mb-3">Keluhan: <em>{{ $ticket->description }}</em></p>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label">Status Penanganan</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Menunggu Respon (Pending)</option>
                                                        <option value="proses" {{ $ticket->status == 'proses' ? 'selected' : '' }}>Sedang Diproses (Teknisi Jalan)</option>
                                                        <option value="selesai" {{ $ticket->status == 'selesai' ? 'selected' : '' }}>Selesai Diperbaiki</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada laporan dari pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
