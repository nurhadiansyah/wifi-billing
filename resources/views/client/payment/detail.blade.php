@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tagihan /</span> Instruksi Pembayaran</h4>

<div class="card mb-4">
    <h5 class="card-header">Detail Transaksi Tripay</h5>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p>No. Referensi: <strong>{{ $transaction['reference'] ?? $bill->tripay_reference }}</strong></p>
                <p>Metode: <strong>{{ $transaction['payment_name'] ?? $bill->payment_method }}</strong></p>
                <p>Total Bayar: <strong class="text-success fs-5">Rp {{ number_format($transaction['amount'] ?? $bill->amount, 0, ',', '.') }}</strong></p>
                <p>Status: 
                    @if(($transaction['status'] ?? '') === 'PAID')
                        <span class="badge bg-success">LUNAS</span>
                    @else
                        <span class="badge bg-warning">MENUNGGU PEMBAYARAN</span>
                    @endif
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                @if(!empty($transaction['pay_code']))
                    <p class="mb-1 text-muted">Kode Pembayaran / Nomor VA:</p>
                    <h3 class="text-primary fw-bold">{{ $transaction['pay_code'] }}</h3>
                @endif
                @if(!empty($transaction['qr_url']))
                    <p class="mb-1 text-muted">Scan QRIS:</p>
                    <img src="{{ $transaction['qr_url'] }}" alt="QRIS" class="img-fluid border p-2" style="max-width: 200px;" />
                @endif
            </div>
        </div>

        @if(!empty($transaction['instructions']))
            <hr class="my-4">
            <h6 class="fw-bold mb-3">Cara Pembayaran:</h6>
            <div class="accordion" id="accordionInstructions">
                @foreach($transaction['instructions'] as $index => $instruction)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                {{ $instruction['title'] }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" data-bs-parent="#accordionInstructions">
                            <div class="accordion-body">
                                <ol>
                                    @foreach($instruction['steps'] as $step)
                                        <li>{!! $step !!}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <!-- Tombol yang sudah ada sebelumnya -->
            <a href="{{ url('/client/tagihan') }}" class="btn btn-secondary">Kembali ke Daftar Tagihan</a>
            
            <!-- TOMBOL BATAL BARU -->
            <form action="{{ route('client.payment.cancel', $bill->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pembayaran ini agar bisa ganti metode pembayaran?');">
                @csrf
                <button type="submit" class="btn btn-danger">Batalkan Pembayaran</button>
            </form>
        </div>
    </div>
</div>
@endsection
