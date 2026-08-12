@extends('layouts.admin_master')

@section('content')
<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Manajemen /</span> Data Pelanggan</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card">
    <!-- BAGIAN HEADER: TOMBOL TAMBAH + FORM PENCARIAN & FILTER -->
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0">Daftar Pelanggan WiFi</h5>
        
        <!-- Form Filter & Search Sederhana (Menggunakan GET Request) -->
        <form method="GET" action="{{ route('admin.pelanggan.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
            <!-- Input Pencarian Nama/Email -->
            <div class="input-group input-group-merge" style="width: 220px;">
                <span class="input-group-text"><i class="bx bx-search"></i></span>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama..." value="{{ request('search') }}">
            </div>

            <!-- Dropdown Filter Status Pelanggan -->
            <select name="status" class="form-select form-select-sm" style="width: 130px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="diisolir" {{ request('status') == 'diisolir' ? 'selected' : '' }}>Diisolir</option>
            </select>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                    <i class="bx bx-reset"></i>
                </a>
            @endif
        </form>

        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalImport">
            <i class='bx bx-import me-1'></i> Import Excel
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class='bx bx-plus me-1'></i> Tambah Pelanggan
        </button>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Kontak</th>
                    <th>Paket Internet</th>
                    <th>Router Info</th>
                    <th>NAS</th>
                    <th>Tgl Aktivasi</th>
                    <th>Status</th>
                    <th>Tgl Tagihan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $customer->name }}</strong><br>
                            <small class="text-muted">{{ $customer->email }}</small>
                        </td>
                        <td>{{ $customer->phone ?? '-' }}</td>
                        <td>
                            @if($customer->package)
                                <span class="badge bg-label-primary">{{ $customer->package->name }} ({{ $customer->package->speed }})</span><br>
                                <small class="text-muted">Rp {{ number_format($customer->package->price, 0, ',', '.') }}</small>
                            @else
                                <span class="badge bg-label-warning">Belum ada paket</span>
                            @endif
                        </td>
                        <td>
                            <small>User: <strong>{{ $customer->router_user ?? '-' }}</strong></small><br>
                            <small>Pass: <strong>{{ $customer->router_password ?? '-' }}</strong></small><br>
                            <small class="text-muted">Profile: {{ $customer->router_profile ?? '-' }}</small>
                        </td>
                        <td>{{ $customer->router_nas ?? '-' }}</td>
                        <td>{{ $customer->activation_date ? \Carbon\Carbon::parse($customer->activation_date)->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($customer->status == 'aktif')
                                <span class="badge bg-label-success">Aktif</span>
                            @else
                                <span class="badge bg-label-danger">Diisolir</span>
                            @endif
                        </td>
                        <td>
                            @if($customer->tanggal_tagihan)
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $tgl = $customer->tanggal_tagihan;
                                    $daysInMonth = $today->daysInMonth;
                                    $safeDay = $tgl > $daysInMonth ? $daysInMonth : $tgl;
                                    $tagihanDate = \Carbon\Carbon::createFromDate($today->year, $today->month, $safeDay);
                                    
                                    if ($today->day > $safeDay) {
                                        $tagihanDate->addMonthNoOverflow();
                                        $tagihanDate->day = min($tgl, $tagihanDate->daysInMonth);
                                    }
                                    
                                    $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                    $namaBulan = $bulanIndo[$tagihanDate->month - 1];
                                @endphp
                                <span class="badge bg-info text-dark">{{ $tagihanDate->day }} {{ $namaBulan }} {{ $tagihanDate->year }}</span>
                                @php
                                    $unpaidCount = \App\Models\Invoice::where('user_id', $customer->id)->where('status', 'unpaid')->count();
                                @endphp
                                @if($unpaidCount > 0)
                                    <div class="mt-1"><span class="badge bg-label-danger" style="font-size: 10px;">Belum Bayar</span></div>
                                @else
                                    <div class="mt-1"><span class="badge bg-label-success" style="font-size: 10px;">Lunas</span></div>
                                @endif
                            @else
                                <span class="badge bg-secondary">Belum Diatur</span>
                            @endif
                        </td>
                        <td>
                            <!-- Tombol Edit -->
                            <button type="button" class="btn btn-sm btn-icon btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $customer->id }}" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.pelanggan.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- MODAL EDIT PELANGGAN (DENGAN OPSI UBAH STATUS) -->
                    <div class="modal fade" id="modalEdit{{ $customer->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Data Pelanggan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.pelanggan.update', $customer->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col mb-0">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $customer->email }}" required>
                                            </div>
                                            <div class="col mb-0">
                                                <label class="form-label">No. HP / WA</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Pilih Paket WiFi</label>
                                            <select name="package_id" class="form-select" required>
                                                <option value="">-- Pilih Paket --</option>
                                                @foreach($packages as $pkg)
                                                    <option value="{{ $pkg->id }}" {{ $customer->package_id == $pkg->id ? 'selected' : '' }}>
                                                        {{ $pkg->name }} ({{ $pkg->speed }}) - Rp {{ number_format($pkg->price, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <!-- TAMBAHAN: PILIHAN STATUS AKUN (AKTIF / DIISOLIR) -->
                                        <div class="mb-3">
                                            <label class="form-label">Status Akun / Layanan</label>
                                            <select name="status" class="form-select" required>
                                                <option value="aktif" {{ $customer->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="diisolir" {{ $customer->status == 'diisolir' ? 'selected' : '' }}>Diisolir</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Alamat Pemasangan</label>
                                            <textarea name="address" class="form-control" rows="2">{{ $customer->address }}</textarea>
                                        </div>
                                        
                                        <hr class="my-3">
                                        <h6 class="text-muted">Data Router & Aktivasi (Opsional)</h6>
                                        <div class="row g-2 mb-3">
                                            <div class="col mb-0">
                                                <label class="form-label">Router User</label>
                                                <input type="text" name="router_user" class="form-control" value="{{ $customer->router_user }}">
                                            </div>
                                            <div class="col mb-0">
                                                <label class="form-label">Router Password</label>
                                                <input type="text" name="router_password" class="form-control" value="{{ $customer->router_password }}">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col mb-0">
                                                <label class="form-label">Router Profile</label>
                                                <input type="text" name="router_profile" class="form-control" value="{{ $customer->router_profile }}">
                                            </div>
                                            <div class="col mb-0">
                                                <label class="form-label">NAS</label>
                                                <input type="text" name="router_nas" class="form-control" value="{{ $customer->router_nas }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Tanggal Aktivasi</label>
                                            <input type="date" name="activation_date" class="form-control" value="{{ $customer->activation_date ? \Carbon\Carbon::parse($customer->activation_date)->format('Y-m-d') : '' }}">
                                        </div>
                                        <hr class="my-3">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_tagihan">Tanggal Jatuh Tempo (Siklus Bulanan)</label>
                                            @php
                                                $tgl = $customer->tanggal_tagihan ?: 1;
                                                $safeDay = min($tgl, \Carbon\Carbon::now()->daysInMonth);
                                                $dummyDate = \Carbon\Carbon::now()->setDay($safeDay)->format('Y-m-d');
                                            @endphp
                                            <input type="date" name="tanggal_tagihan" class="form-control" value="{{ $customer->tanggal_tagihan ? $dummyDate : '' }}" required>
                                            <small class="text-muted">Sistem hanya akan mengambil <strong>Tanggal (Hari)</strong>-nya saja sebagai siklus bulanan.</small>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Password Baru <span class="text-muted" style="font-size: 11px;">(Kosongkan jika tidak ingin diubah)</span></label>
                                            <input type="text" name="password" class="form-control" placeholder="Masukkan password baru (Opsional)">
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
                        <td colspan="10" class="text-center py-4">Tidak ada data pelanggan yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $customers->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PELANGGAN -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.pelanggan.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama..." required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                        </div>
                        <div class="col mb-0">
                            <label class="form-label">No. HP / WA</label>
                            <input type="text" name="phone" class="form-control" placeholder="0812xxxx" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Paket WiFi</label>
                        <select name="package_id" class="form-select" required>
                            <option value="">-- Pilih Paket --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->name }} ({{ $pkg->speed }}) - Rp {{ number_format($pkg->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Pemasangan</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Detail alamat..."></textarea>
                    </div>

                    <hr class="my-3">
                    <h6 class="text-muted">Data Router & Aktivasi (Opsional)</h6>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label class="form-label">Router User</label>
                            <input type="text" name="router_user" class="form-control">
                        </div>
                        <div class="col mb-0">
                            <label class="form-label">Router Password</label>
                            <input type="text" name="router_password" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col mb-0">
                            <label class="form-label">Router Profile</label>
                            <input type="text" name="router_profile" class="form-control">
                        </div>
                        <div class="col mb-0">
                            <label class="form-label">NAS</label>
                            <input type="text" name="router_nas" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Aktivasi</label>
                        <input type="date" name="activation_date" class="form-control">
                    </div>
                    <hr class="my-3">
                    <div class="form-group mb-3">
                        <label for="tanggal_tagihan">Tanggal Jatuh Tempo (Siklus Bulanan)</label>
                        <input type="date" name="tanggal_tagihan" class="form-control" required>
                        <small class="text-muted">Sistem hanya akan mengambil <strong>Tanggal (Hari)</strong>-nya saja sebagai siklus bulanan.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL IMPORT EXCEL -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Data Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.pelanggan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Perhatian:</strong> Pastikan baris pertama Excel Anda memiliki judul kolom persis seperti ini:<br>
                        <code>no</code>, <code>user</code>, <code>password</code>, <code>profile</code>, <code>nas</code>, <code>Harga Paket</code>, <code>Tanggal Aktivasi</code>, <code>Tanggal Pembayaran</code>, <code>name</code>, <code>phone</code>, <code>address</code>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File Excel (.xlsx / .csv)</label>
                        <input type="file" name="file_excel" class="form-control" required accept=".xlsx, .xls, .csv">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Mulai Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection