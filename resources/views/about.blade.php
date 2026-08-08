@extends('layouts.guest')

@section('content')
<div class="container py-5 mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-5 text-center">
            <h1 class="mb-4 fw-bold text-primary">Tentang Kami</h1>
            <p class="lead text-muted mb-5">DreamNet Indonesia adalah penyedia layanan internet (ISP) yang berdedikasi untuk memberikan koneksi tercepat dan stabil untuk keluarga dan bisnis Anda.</p>

            <div class="row text-start mt-4">
                <div class="col-md-6 mb-4">
                    <h4 class="fw-bold"><i class="bx bx-rocket text-primary me-2"></i> Misi Kami</h4>
                    <p>Menghadirkan layanan internet yang terjangkau, cepat, dan tanpa batas untuk mendukung digitalisasi masyarakat hingga ke pelosok daerah.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h4 class="fw-bold"><i class="bx bx-shield-quarter text-primary me-2"></i> Visi Kami</h4>
                    <p>Menjadi pelopor penyedia layanan telekomunikasi yang paling andal dengan tingkat kepuasan pelanggan tertinggi di Indonesia.</p>
                </div>
            </div>

            <hr class="my-5">

            <h4 class="fw-bold mb-4">Layanan Unggulan</h4>
            <div class="row text-start">
                <div class="col-md-4 mb-3">
                    <div class="p-4 bg-light rounded">
                        <h6 class="fw-bold">Koneksi Fiber Optic</h6>
                        <p class="small mb-0">Teknologi serat optik terbaru untuk kestabilan ping dan kecepatan unduh.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-4 bg-light rounded">
                        <h6 class="fw-bold">Dukungan 24/7</h6>
                        <p class="small mb-0">Tim teknisi kami selalu siap membantu masalah Anda kapan saja.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="p-4 bg-light rounded">
                        <h6 class="fw-bold">Pembayaran Mudah</h6>
                        <p class="small mb-0">Sistem penagihan otomatis dengan dukungan Virtual Account dan QRIS.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
