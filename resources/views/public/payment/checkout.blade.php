<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tagihan - {{ $bill->invoice_number }}</title>
    <!-- Use Bootstrap 5 for a clean layout -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f9;
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            border: 0;
            box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.15);
            border-radius: 0.5rem;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #d9dee3;
            font-weight: 600;
        }
        .badge {
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="container pb-5">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary">Pembayaran Tagihan WiFi</h3>
        <p class="text-muted">No. Invoice: {{ $bill->invoice_number }}</p>
    </div>

    <!-- ALERT JIKA DAFTAR METODE PEMBAYARAN GAGAL DIAMBIL -->
    @if(empty($channels))
        <div class="alert alert-danger d-flex p-3 align-items-center shadow-sm">
            <i class="bx bx-error-circle fs-3 me-3"></i>
            <div>
                <h6 class="mb-1 text-danger fw-bold">Sedang Gangguan!</h6>
                <span class="fs-6">Sistem pembayaran saat ini tidak tersedia. Silakan hubungi admin.</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- BAGIAN RINGKASAN TAGIHAN -->
        <div class="col-md-5 mb-4">
            <div class="card h-100">
                <div class="card-header py-3">Ringkasan Tagihan</div>
                <div class="card-body py-4">
                    <p class="text-muted mb-1">Pelanggan</p>
                    <h6 class="fw-bold mb-3">{{ $bill->user ? $bill->user->name : 'Pelanggan' }}</h6>
                    
                    <p class="text-muted mb-1">Jatuh Tempo</p>
                    <h6 class="fw-bold mb-3">{{ \Carbon\Carbon::parse($bill->due_date)->format('d F Y') }}</h6>

                    <p class="text-muted mb-1">Nominal</p>
                    <h4 class="fw-bold text-primary mb-3">Rp {{ number_format($bill->amount ?? 0, 0, ',', '.') }}</h4>
                    
                    <p class="text-muted mb-0">Status: 
                        @if($bill->status == 'unpaid')
                            <span class="badge bg-warning text-dark">Belum Bayar</span>
                        @else
                            <span class="badge bg-success">Sudah Lunas</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- BAGIAN FORM PEMBAYARAN -->
        <div class="col-md-7 mb-4">
            <div class="card h-100">
                <div class="card-header py-3">Pilih Metode Pembayaran</div>
                <div class="card-body py-4">
                    @if($bill->status == 'paid')
                        <div class="alert alert-success text-center h-100 d-flex flex-column justify-content-center align-items-center">
                            <i class='bx bx-check-circle fs-1 mb-2'></i>
                            <h5>Tagihan ini sudah lunas.</h5>
                            <p class="mb-0">Terima kasih atas pembayaran Anda.</p>
                        </div>
                    @else
                        <form action="{{ route('public.payment.store', $bill->invoice_number) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label text-muted">Metode Pembayaran</label>
                                <select name="method" class="form-select form-select-lg" required {{ empty($channels) ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Metode Pembayaran --</option>
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
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100" {{ empty($channels) ? 'disabled' : '' }}>
                                <i class='bx bx-credit-card me-1'></i> Bayar Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-3 text-muted">
        <small>&copy; {{ date('Y') }} Layanan Internet WiFi. Dilindungi dengan sistem keamanan yang terenkripsi.</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
