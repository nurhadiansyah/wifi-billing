@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tagihan /</span> Pilih Metode Pembayaran</h4>

<!-- ALERT JIKA DAFTAR METODE PEMBAYARAN GAGAL DIAMBIL -->
@if(empty($channels))
    <div class="alert alert-danger d-flex p-3 align-items-center">
        <i class="bx bx-error-circle fs-3 me-3"></i>
        <div>
            <h6 class="mb-1 text-danger fw-bold">Gagal Menghubungi Server Tripay!</h6>
            <span class="fs-6">Daftar metode pembayaran tidak muncul karena API Key tidak valid. Pastikan Anda sudah mengisi <strong>TRIPAY_API_KEY</strong> di file <strong>.env</strong> Anda dengan benar.</span>
        </div>
    </div>
@endif

<div class="row">
    <!-- BAGIAN RINGKASAN TAGIHAN KIRI -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <h5 class="card-header">Ringkasan Tagihan</h5>
            <div class="card-body">
                <!-- Otomatis mendeteksi kolom 'bulan' atau 'month' -->
                <p>Bulan Tagihan: <strong>{{ $bill->bulan ?? $bill->month ?? 'Tagihan Bulanan' }}</strong></p>
                
                <!-- Otomatis mendeteksi kolom 'total' atau 'amount' -->
                <p>Nominal: <strong class="text-primary fs-4">Rp {{ number_format($bill->total ?? $bill->amount ?? 0, 0, ',', '.') }}</strong></p>
                
                <p class="text-muted mb-0">Status: <span class="badge bg-label-warning">Belum Bayar</span></p>
            </div>
        </div>
    </div>

    <!-- BAGIAN FORM PEMBAYARAN KANAN -->
    <div class="col-md-7">
        <div class="card">
            <h5 class="card-header">Pilih Metode Pembayaran Otomatis</h5>
            <div class="card-body">
                <form action="{{ route('client.payment.store', $bill->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran (Virtual Account / QRIS / Convenience Store)</label>
                        <select name="method" class="form-select" required {{ empty($channels) ? 'disabled' : '' }}>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            
                            <!-- Looping data metode pembayaran dengan aman -->
                            @if(!empty($channels))
                                @foreach($channels as $channel)
                                    @if(isset($channel['active']) && $channel['active'] == true)
                                        <option value="{{ $channel['code'] }}">
                                            {{ $channel['name'] }}
                                        </option>
                                    @endif
                                @endforeach
                            @endif

                        </select>
                        
                        @if(empty($channels))
                            <small class="text-danger mt-2 d-block">Metode pembayaran dikunci hingga konfigurasi Tripay diperbaiki.</small>
                        @endif
                    </div>

                    <!-- Tombol bayar akan mati otomatis jika metode pembayaran gagal dimuat -->
                    <button type="submit" class="btn btn-primary w-100" {{ empty($channels) ? 'disabled' : '' }}>
                        Buat Pembayaran Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
