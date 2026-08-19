@extends('layouts.admin_master')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Sistem /</span> Pengaturan</h4>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Konfigurasi Pengingat & Fonnte</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.pengaturan.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" for="fonnte_token">Token API Fonnte</label>
                        <input type="text" class="form-control" id="fonnte_token" name="fonnte_token" value="{{ old('fonnte_token', $setting->fonnte_token) }}" placeholder="Masukkan token fonnte.com" />
                        <div class="form-text">
                            Token ini digunakan untuk mengirim pesan WhatsApp otomatis ke pelanggan.
                        </div>
                    </div>

                    <div class="mb-3 form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" id="auto_reminder" name="auto_reminder" {{ old('auto_reminder', $setting->auto_reminder) ? 'checked' : '' }}>
                        <label class="form-check-label" for="auto_reminder">Aktifkan Pengiriman WhatsApp Otomatis</label>
                        <div class="form-text">
                            Jika diaktifkan, sistem akan otomatis mengirim WhatsApp peringatan tagihan ketika Invoice dicetak/ter-generate.
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
