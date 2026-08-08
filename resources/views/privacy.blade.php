@extends('layouts.frontend')

@section('content')
<div class="container py-5 mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-5">
            <h1 class="mb-4 fw-bold text-primary">Kebijakan Privasi</h1>
            <p class="text-muted mb-4">Terakhir Diperbarui: {{ date('d M Y') }}</p>

            <h4 class="fw-bold mt-4">1. Pendahuluan</h4>
            <p>Selamat datang di DreamNet Indonesia. Kami sangat menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi yang Anda berikan kepada kami.</p>

            <h4 class="fw-bold mt-4">2. Pengumpulan Data</h4>
            <p>Kami mengumpulkan informasi yang Anda berikan secara langsung saat pendaftaran layanan, seperti Nama, Alamat Email, Nomor Telepon, dan Alamat Pemasangan. Kami juga mencatat data transaksi saat Anda melakukan pembayaran tagihan.</p>

            <h4 class="fw-bold mt-4">3. Penggunaan Data</h4>
            <p>Data yang kami kumpulkan digunakan secara eksklusif untuk:</p>
            <ul>
                <li>Memproses pendaftaran dan aktivasi layanan internet Anda.</li>
                <li>Memproses pembayaran tagihan Anda melalui payment gateway (Tripay).</li>
                <li>Menghubungi Anda terkait masalah teknis, pemeliharaan, atau pemberitahuan tagihan.</li>
            </ul>

            <h4 class="fw-bold mt-4">4. Keamanan Data</h4>
            <p>Kami menerapkan langkah-langkem keamanan teknis yang ketat untuk mencegah akses, pengungkapan, atau modifikasi yang tidak sah terhadap data pribadi Anda.</p>

            <h4 class="fw-bold mt-4">5. Hubungi Kami</h4>
            <p>Jika Anda memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi tim dukungan kami melalui layanan pelanggan.</p>
        </div>
    </div>
</div>
@endsection
