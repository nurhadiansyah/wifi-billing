@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen Sistem /</span> Data Teknisi Lapangan</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Teknisi Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ url('/admin/teknisi') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nama Teknisi</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Budi" required />
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">No. WhatsApp</label>
                    <input type="text" name="phone" class="form-control" placeholder="Contoh: 62812..." required />
                </div>
                
                <!-- MENGGUNAKAN DATALIST UNTUK AREA -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Area Tugas</label>
                    <!-- Atribut list="areaOptions" menghubungkan input dengan datalist di bawahnya -->
                    <input type="text" name="area" class="form-control" list="areaOptions" placeholder="Pilih atau ketik baru..." required autocomplete="off">
                    
                    <datalist id="areaOptions">
                        <option value="Area Utara">
                        <option value="Area Selatan">
                        <option value="Area Timur">
                        <option value="Area Barat">
                        <option value="Pusat Kota">
                    </datalist>
                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <h5 class="card-header">Daftar Teknisi Aktif</h5>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Teknisi</th>
                    <th>No. WhatsApp</th>
                    <th>Area Tugas</th> <!-- Tambahan Kolom Area -->
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($technicians as $index => $tech)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $tech->name }}</strong></td>
                        <td>{{ $tech->phone }}</td>
                        <td><span class="badge bg-label-primary">{{ $tech->area ?? '-' }}</span></td>
                        <td>
                            <!-- Tombol Edit Modal -->
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTech{{ $tech->id }}">
                                Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ url('/admin/teknisi/' . $tech->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus teknisi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>

                            <!-- Modal Edit Teknisi -->
                            <div class="modal fade" id="editTech{{ $tech->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Data Teknisi</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ url('/admin/teknisi/' . $tech->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Teknisi</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $tech->name }}" required />
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">No. WhatsApp</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ $tech->phone }}" required />
                                                </div>
                                                <!-- FORM EDIT AREA (Bisa diubah/diketik juga) -->
                                                <div class="mb-3">
                                                    <label class="form-label">Area Tugas</label>
                                                    <input type="text" name="area" class="form-control" list="areaOptions" value="{{ $tech->area }}" required autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Perbarui</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada data teknisi yang ditambahkan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection