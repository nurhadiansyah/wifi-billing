@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tagihan /</span> Daftar Tagihan Saya</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <h5 class="card-header">Riwayat Tagihan Bulanan</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bulan / Keterangan</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($invoices as $index => $invoice)
                    @php
                        // Deteksi status tagihan secara otomatis, tidak peduli huruf besar atau kecil
                        $status_tagihan = strtolower($invoice->status ?? '');
                        
                        // Kumpulan kata yang menandakan tagihan belum dibayar
                        $is_unpaid = in_array($status_tagihan, ['belum', 'unpaid', 'pending', 'menunggu']);
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $invoice->month ?? 'Tagihan WiFi Bulanan' }}</strong>
                        </td>
                        <!-- Ganti $invoice->amount dengan $invoice->total jika di database Anda menggunakan kata 'total' -->
                        <td>Rp {{ number_format($invoice->amount ?? 0, 0, ',', '.') }}</td>
                        
                        <!-- Kolom Status (Badge) -->
                        <td>
                            @if($is_unpaid)
                                <span class="badge bg-label-danger"><i class="bx bx-x"></i> Belum Bayar</span>
                            @else
                                <span class="badge bg-label-success"><i class="bx bx-check"></i> Lunas</span>
                            @endif
                        </td>
                        
                        <!-- Kolom Aksi (Tombol Bayar Tripay) -->
                        <td>
                            @if($is_unpaid)
                                @if($invoice->tripay_reference)
                                    <a href="{{ route('client.payment.detail', $invoice->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-info-circle me-1"></i> Cara Bayar
                                    </a>
                                @else
                                    <a href="{{ route('client.payment.checkout', $invoice->id) }}" class="btn btn-sm btn-success">
                                        <i class="bx bx-wallet me-1"></i> Bayar
                                    </a>
                                @endif
                            @else
                                <span class="badge bg-success"><i class="bx bx-check-double me-1"></i> Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data tagihan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
