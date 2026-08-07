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
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
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
    </div>
</div>

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
</script>
@endsection