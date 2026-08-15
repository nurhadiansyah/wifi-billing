<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tagihan - {{ $user->name }}</title>
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
        <h3 class="fw-bold text-primary">Daftar Tagihan Anda</h3>
        <p class="text-muted">Halo, <strong>{{ $user->name }}</strong></p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header py-3">Tagihan Belum Dibayar</div>
        <div class="card-body py-4">
            @php
                $unpaidInvoices = $user->invoices->where('status', 'unpaid');
            @endphp

            @if($unpaidInvoices->isEmpty())
                <div class="alert alert-success text-center h-100 d-flex flex-column justify-content-center align-items-center mb-0">
                    <i class='bx bx-check-circle fs-1 mb-2'></i>
                    <h5>Hore! Anda tidak memiliki tagihan yang belum dibayar.</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No. Invoice</th>
                                <th>Bulan</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unpaidInvoices as $invoice)
                                <tr>
                                    <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('F Y') }}</td>
                                    <td>
                                        @if(\Carbon\Carbon::parse($invoice->due_date)->isPast())
                                            <span class="text-danger fw-bold">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                                        @else
                                            {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}
                                        @endif
                                    </td>
                                    <td class="fw-bold text-primary">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('public.payment.checkout', $invoice->invoice_number) }}" class="btn btn-sm btn-primary">
                                            <i class='bx bx-credit-card me-1'></i> Bayar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ url('/') }}" class="btn btn-outline-secondary"><i class='bx bx-arrow-back me-1'></i> Kembali ke Beranda</a>
    </div>

    <div class="text-center mt-4 text-muted">
        <small>&copy; {{ date('Y') }} Layanan Internet WiFi. Dilindungi dengan sistem keamanan yang terenkripsi.</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
