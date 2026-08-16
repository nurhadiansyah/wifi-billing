@extends('layouts.admin_master')

@section('content')
<style>
    /* Premium Dashboard Styles */
    .dashboard-header {
        background: linear-gradient(135deg, #696cff, #8a8dff);
        color: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px 0 rgba(105, 108, 255, 0.4);
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-header h4 {
        color: white;
        margin-bottom: 5px;
        font-weight: 700;
    }

    .dashboard-header p {
        margin-bottom: 0;
        opacity: 0.9;
    }

    .dashboard-header::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .stat-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        background: #fff;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    .stat-card .icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }

    .bg-light-primary { background: rgba(105, 108, 255, 0.1); color: #696cff; }
    .bg-light-success { background: rgba(113, 221, 55, 0.1); color: #71dd37; }
    .bg-light-danger { background: rgba(255, 62, 29, 0.1); color: #ff3e1d; }
    .bg-light-warning { background: rgba(255, 171, 0, 0.1); color: #ffab00; }
    .bg-light-info { background: rgba(3, 195, 236, 0.1); color: #03c3ec; }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #566a7f;
        margin-bottom: 4px;
    }

    .stat-label {
        color: #a1acb8;
        font-size: 0.875rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-container {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        background: #fff;
        padding: 20px;
    }

    .table-title {
        font-weight: 700;
        color: #566a7f;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>

<div class="row">
    <!-- Header Welcome -->
    <div class="col-12">
        <div class="dashboard-header">
            <h4>Selamat Datang, {{ Auth::user()->name }}! 🚀</h4>
            <p>Ini adalah ringkasan performa jaringan dan keuangan Anda hari ini.</p>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card h-100 p-3">
            <div class="icon-wrapper bg-light-primary">
                <i class='bx bx-group'></i>
            </div>
            <div class="stat-value">{{ number_format($totalCustomers) }}</div>
            <div class="stat-label">Total Pelanggan</div>
            <div class="mt-2 text-sm">
                <span class="text-success fw-semibold"><i class='bx bx-up-arrow-alt'></i> {{ number_format($activeCustomers) }} Aktif</span>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card h-100 p-3">
            <div class="icon-wrapper bg-light-success">
                <i class='bx bx-wallet'></i>
            </div>
            <div class="stat-value">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Bulan Ini</div>
            <div class="mt-2 text-sm text-muted">Total tagihan terbayar</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card h-100 p-3">
            <div class="icon-wrapper bg-light-danger">
                <i class='bx bx-trending-down'></i>
            </div>
            <div class="stat-value">Rp {{ number_format($unpaidInvoices, 0, ',', '.') }}</div>
            <div class="stat-label">Tagihan Tertunggak</div>
            <div class="mt-2 text-sm text-muted">Belum lunas keseluruhan</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card stat-card h-100 p-3">
            <div class="icon-wrapper bg-light-warning">
                <i class='bx bx-support'></i>
            </div>
            <div class="stat-value">{{ number_format($openTicketsCount) }}</div>
            <div class="stat-label">Tiket Terbuka</div>
            <div class="mt-2 text-sm">
                @if($openTicketsCount > 0)
                    <span class="text-danger fw-semibold"><i class='bx bx-error-circle'></i> Butuh penanganan</span>
                @else
                    <span class="text-success fw-semibold"><i class='bx bx-check-circle'></i> Semua aman</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="col-lg-7 col-md-12 mb-4">
        <div class="table-container h-100">
            <h5 class="table-title"><i class='bx bx-receipt text-primary fs-4'></i> 5 Tagihan Terbaru</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Nominal</th>
                            <th>Jatuh Tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $invoice)
                            <tr>
                                <td>
                                    <strong>{{ $invoice->user ? $invoice->user->name : 'Deleted User' }}</strong><br>
                                    <small class="text-muted">{{ $invoice->invoice_number }}</small>
                                </td>
                                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                                <td>
                                    @if($invoice->status == 'paid')
                                        <span class="badge bg-label-success">Lunas</span>
                                    @else
                                        <span class="badge bg-label-danger">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Belum ada data tagihan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-md-12 mb-4">
        <div class="table-container h-100">
            <h5 class="table-title"><i class='bx bx-message-square-error text-warning fs-4'></i> Laporan Terbaru</h5>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pelapor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTickets as $ticket)
                            <tr>
                                <td>
                                    <strong>{{ $ticket->user ? $ticket->user->name : 'Deleted User' }}</strong><br>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;">
                                        {{ $ticket->subject }}
                                    </small>
                                </td>
                                <td>
                                    @if($ticket->status == 'open')
                                        <span class="badge bg-label-warning">Open</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span class="badge bg-label-info">Diproses</span>
                                    @else
                                        <span class="badge bg-label-success">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">
                                    <i class='bx bx-check-shield fs-1 text-success mb-2 d-block'></i>
                                    Tidak ada laporan masalah!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
