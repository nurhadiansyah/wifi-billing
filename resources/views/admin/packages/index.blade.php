@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Paket WiFi</h4>

<!-- Notifikasi Sukses -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Paket Internet</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPaket">
            <i class='bx bx-plus me-1'></i> Tambah Paket
        </button>
    </div>
    
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Paket</th>
                    <th>Kecepatan</th>
                    <th>Harga / Bulan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($packages as $index => $package)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $package->name }}</strong></td>
                        <td><span class="badge bg-label-info">{{ $package->speed }}</span></td>
                        <td>Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                        <td>{{ $package->description ?? '-' }}</td>
                        <td>
                            <!-- Tombol Edit -->
                            <button type="button" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalEditPaket{{ $package->id }}" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.paket.destroy', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus paket ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- MODAL EDIT PAKET -->
                    <div class="modal fade" id="modalEditPaket{{ $package->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Paket WiFi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.paket.update', $package->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Paket</label>
                                            <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col mb-0">
                                                <label class="form-label">Kecepatan</label>
                                                <input type="text" name="speed" class="form-control" value="{{ $package->speed }}" placeholder="Contoh: 10 Mbps" required>
                                            </div>
                                            <div class="col mb-0">
                                                <label class="form-label">Harga (Rp)</label>
                                                <input type="number" name="price" class="form-control" value="{{ $package->price }}" placeholder="150000" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Keterangan / Deskripsi</label>
                                            <textarea name="description" class="form-control" rows="2">{{ $package->description }}</textarea>
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
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Belum ada data paket internet yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL TAMBAH PAKET -->
<div class="modal fade" id="modalTambahPaket" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Paket WiFi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.paket.store') }}">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nama Paket</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Paket Warga / Silver" value="{{ old('name') }}" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label class="form-label">Kecepatan</label>
                            <input type="text" name="speed" class="form-control" placeholder="Contoh: 10 Mbps" value="{{ old('speed') }}" required>
                        </div>
                        <div class="col mb-0">
                            <label class="form-label">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control" placeholder="150000" value="{{ old('price') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan / Deskripsi</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Contoh: Unlimited tanpa batas kuota...">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalTambahPaket'));
        myModal.show();
    });
</script>
@endif

@endsection
