@extends('layouts.admin_master')

@section('content')
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span> Beranda</h4>

    <div class="row">
        <!-- Kartu Selamat Datang -->
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat datang kembali, {{ Auth::user()->name }}! 🎉</h5>
                            <p class="mb-4">
                                Anda memiliki <span class="fw-bold">0</span> tiket pengaduan pelanggan yang belum dibaca hari ini. Pantau terus jaringan Anda.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kartu Statistik -->
        <div class="col-lg-4 col-md-4 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1">Total Pelanggan</span>
                    <h3 class="card-title mb-2">500</h3>
                    <small class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i> Aktif</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <span class="fw-semibold d-block mb-1">Pendapatan Bulan Ini</span>
                    <h3 class="card-title mb-2">Rp 0</h3>
                    <small class="text-info fw-semibold"><i class='bx bx-wallet'></i> Belum ada tagihan terbayar</small>
                </div>
            </div>
        </div>
    </div>
@endsection