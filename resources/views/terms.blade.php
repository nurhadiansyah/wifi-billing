@extends('layouts.guest')

@section('content')
<div class="container py-5 mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body p-5">
            <h1 class="mb-4 fw-bold text-primary">Syarat & Ketentuan</h1>
            <p class="text-muted mb-4">Terakhir Diperbarui: {{ date('d M Y') }}</p>

            <h4 class="fw-bold mt-4">1. Penerimaan Syarat</h4>
            <p>Dengan mendaftar dan menggunakan layanan internet DreamNet Indonesia, Anda menyetujui untuk terikat oleh Syarat dan Ketentuan ini. Jika Anda tidak setuju, mohon untuk tidak menggunakan layanan kami.</p>

            <h4 class="fw-bold mt-4">2. Kewajiban Pengguna</h4>
            <ul>
                <li>Pengguna wajib membayar tagihan layanan tepat waktu sebelum tanggal jatuh tempo.</li>
                <li>Pengguna dilarang menggunakan koneksi internet untuk kegiatan ilegal, penipuan, atau mendistribusikan perangkat lunak berbahaya.</li>
                <li>Pengguna tidak diperkenankan menjual kembali (resell) layanan internet tanpa izin tertulis dari DreamNet Indonesia.</li>
            </ul>

            <h4 class="fw-bold mt-4">3. Kebijakan Pembayaran & Pemutusan</h4>
            <p>Tagihan akan diterbitkan setiap bulan. Keterlambatan pembayaran setelah tanggal jatuh tempo dapat mengakibatkan isolasi sementara atau pemutusan layanan secara sepihak oleh sistem secara otomatis. Reaktivasi layanan mungkin dikenakan biaya tambahan.</p>

            <h4 class="fw-bold mt-4">4. Perubahan Layanan</h4>
            <p>DreamNet Indonesia berhak sewaktu-waktu mengubah struktur harga, batasan kuota (FUP), atau spesifikasi layanan dengan memberikan pemberitahuan sebelumnya kepada pelanggan melalui Email atau WhatsApp.</p>

            <h4 class="fw-bold mt-4">5. Ganti Rugi</h4>
            <p>DreamNet Indonesia tidak bertanggung jawab atas kerugian finansial atau kehilangan data yang diakibatkan oleh gangguan jaringan di luar kendali kami (Force Majeure).</p>
        </div>
    </div>
</div>
@endsection
