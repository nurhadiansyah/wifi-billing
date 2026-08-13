@extends('layouts.admin_master')

@section('content')
<!-- Tambahkan CSS Select2 agar tampilannya rapi menyatu dengan Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Tagihan Pelanggan</h4>

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
    <div class="card-header d-flex flex-column gap-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Daftar Tagihan Bulanan</h5>
            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('admin.tagihan.waBulk') }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin mengirim pesan WhatsApp ke SEMUA pelanggan yang belum lunas? Pastikan token Whatbizz valid.');">
                    @csrf
                    <button type="submit" class="btn btn-info">
                        <i class='bx bxl-whatsapp me-1'></i> Broadcast WA
                    </button>
                </form>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalGenerateBulk">
                    <i class='bx bx-bolt-circle me-1'></i> Buat Tagihan (Massal)
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTagihan">
                    <i class='bx bx-plus me-1'></i> Buat Tagihan Satuan
                </button>
            </div>
        </div>
        
        <form method="GET" action="{{ route('admin.tagihan.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
            <div class="input-group input-group-merge" style="width: 250px;">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari no. tagihan / pelanggan..." value="{{ request('search') }}">
            </div>

            <select name="status" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.tagihan.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                    <i class="bx bx-reset"></i>
                </a>
            @endif
        </form>
    </div>
    
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No. Tagihan</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah Tagihan</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->user->name ?? 'Pelanggan Dihapus' }}</td>
                        <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                        <td><span class="text-danger">{{ $invoice->due_date }}</span></td>
                        <td>
                            @if($invoice->status == 'paid')
                                <span class="badge bg-label-success">Lunas</span>
                            @else
                                <span class="badge bg-label-warning">Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($invoice->status == 'unpaid')
                                <button type="button" class="btn btn-sm btn-icon btn-outline-dark" onclick="copyPaymentLink('{{ route('public.payment.checkout', $invoice->invoice_number) }}')" title="Copy Link Pembayaran">
                                    <i class="bx bx-qr"></i>
                                </button>

                                <form action="{{ route('admin.tagihan.wa', $invoice->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-info" title="Kirim WA Pengingat">
                                        <i class="bx bxl-whatsapp"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.tagihan.paid', $invoice->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-success" title="Tandai Lunas">
                                        <i class="bx bx-check"></i>
                                    </button>
                                </form>
                            @endif

                            <button type="button" class="btn btn-sm btn-icon btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditTagihan{{ $invoice->id }}" title="Edit">
                                <i class="bx bx-edit"></i>
                            </button>

                            <form action="{{ route('admin.tagihan.destroy', $invoice->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada data tagihan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $invoices->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL EDIT TAGIHAN (DILOOP) -->
<!-- ============================================== -->
@foreach($invoices as $invoice)
<div class="modal fade" id="modalEditTagihan{{ $invoice->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Tagihan: {{ $invoice->invoice_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.tagihan.update', $invoice->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jumlah Tagihan (Rp)</label>
                        <input type="number" name="amount" class="form-control" value="{{ (int)$invoice->amount }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" class="form-control" value="{{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Belum Lunas (Unpaid)</option>
                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- ============================================== -->
<!-- MODAL GENERATE TAGIHAN MASSAL -->
<!-- ============================================== -->
<div class="modal fade" id="modalGenerateBulk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Tagihan Otomatis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.tagihan.generateBulk') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <small>Sistem akan otomatis menerbitkan tagihan untuk seluruh pelanggan berdasarkan <strong>harga paket masing-masing</strong>.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Jatuh Tempo Bersama</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Proses Generate Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL BUAT TAGIHAN SATUAN (DENGAN PENCARIAN NAMA) -->
<!-- ============================================== -->
<div class="modal fade" id="modalTambahTagihan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Tagihan Satuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.tagihan.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cari & Pilih Pelanggan</label>
                        <!-- Tambahkan kelas select2-customer di sini -->
                        <select name="user_id" class="form-select select2-customer" required style="width: 100%;">
                            <option value="">-- Ketik nama atau email pelanggan --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Paket WiFi (Menentukan Harga)</label>
                        <select name="package_id" class="form-select" required>
                            <option value="">-- Pilih Paket --</option>
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan Skrip JavaScript untuk Mengaktifkan Select2 di dalam Modal -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi Select2 khusus untuk modal tambah tagihan agar kolom pencariannya bisa diklik
        $('.select2-customer').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#modalTambahTagihan')
        });
    });

    function copyPaymentLink(url) {
        var tempInput = document.createElement("input");
        tempInput.style = "position: absolute; left: -1000px; top: -1000px";
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        alert("Link pembayaran berhasil disalin!\n" + url);
    }
</script>
@endsection