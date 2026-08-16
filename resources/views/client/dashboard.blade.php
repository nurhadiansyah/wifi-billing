@extends('layouts.admin_master')

@section('content')
<div class="row">
    <!-- Ucapan Selamat Datang -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="text-white mb-1">Halo, {{ $user->name }}! 👋</h4>
                    <p class="mb-0">Selamat datang di panel layanan pelanggan WiFi Anda.</p>
                </div>
                <div class="d-none d-md-block">
                    @if($user->status == 'aktif')
                        <span class="badge bg-success">Status: AKTIF</span>
                    @else
                        <span class="badge bg-danger">Status: DIISOLIR</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Paket & Profil (Dibagi 2 Kolom) -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0"><i class='bx bx-wifi me-2'></i>Layanan & Paket Anda</h5>
            </div>
            <div class="card-body pt-4">
                @if($user->package)
                    <div class="mb-3">
                        <small class="text-muted d-block">Nama Paket</small>
                        <span class="fw-bold fs-5">{{ $user->package->name }}</span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted d-block">Kecepatan</small>
                            <span class="badge bg-label-info">{{ $user->package->speed }}</span>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Biaya Bulanan</small>
                            <span class="fw-semibold text-primary">Rp {{ number_format($user->package->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bx bx-wifi-off fs-1 text-muted mb-2"></i>
                        <p class="text-muted mb-0">Anda belum berlangganan paket apapun.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0"><i class='bx bx-user me-2'></i>Informasi Kontak</h5>
            </div>
            <div class="card-body pt-4">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex mb-3">
                        <i class="bx bx-envelope text-primary me-3 fs-4"></i>
                        <div>
                            <span class="fw-semibold d-block">Email</span>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bx bx-phone text-success me-3 fs-4"></i>
                        <div>
                            <span class="fw-semibold d-block">No. HP / WA</span>
                            <small class="text-muted">{{ $user->phone ?? '-' }}</small>
                        </div>
                    </li>
                    <li class="d-flex">
                        <i class="bx bx-map text-warning me-3 fs-4"></i>
                        <div>
                            <span class="fw-semibold d-block">Alamat</span>
                            <small class="text-muted">{{ $user->address ?? 'Belum ada alamat terdaftar' }}</small>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Tagihan -->
    <div class="col-12">
        <div class="card">
            <h5 class="card-header"><i class='bx bx-receipt me-2'></i>Riwayat Tagihan Bulanan</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Tagihan</th>
                            <th>Jatuh Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($user->invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y') }}</td>
                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td>
                                    @if($invoice->status == 'paid')
                                        <span class="badge bg-label-success"><i class="bx bx-check"></i> Lunas</span>
                                    @else
                                        <span class="badge bg-label-danger"><i class="bx bx-x"></i> Belum Lunas</span>
                                    @endif
                                </td>
                                <td>
                                    @if($invoice->status != 'paid')
                                        <a href="{{ route('client.payment.checkout', $invoice->id) }}" class="btn btn-sm btn-primary">Bayar Tagihan</a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Hore! Tidak ada catatan tagihan saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection