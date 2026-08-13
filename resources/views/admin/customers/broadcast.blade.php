@extends('layouts.admin_master')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kirim Broadcast Gangguan Jaringan</h5>
            </div>
            <div class="card-body">
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

                <form action="{{ route('admin.broadcast.send') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" for="nas">Pilih NAS Target</label>
                        <select class="form-select" id="nas" name="nas" required>
                            <option value="">-- Pilih NAS --</option>
                            @foreach($nasList as $nas)
                                <option value="{{ $nas }}">{{ $nas }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">Pesan akan dikirim ke semua pelanggan aktif yang terhubung di NAS ini.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="message">Pesan Broadcast</label>
                        <textarea id="message" name="message" class="form-control" rows="6" placeholder="Contoh: Yth Pelanggan Dreamnet, saat ini sedang terjadi gangguan jaringan pada area Anda. Tim teknisi sedang melakukan perbaikan. Mohon maaf atas ketidaknyamanan ini." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin mengirim pesan broadcast ke seluruh pelanggan di NAS tersebut?')">
                        <i class="bx bx-send me-1"></i> Kirim Broadcast
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
