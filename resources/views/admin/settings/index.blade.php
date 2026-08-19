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

                    <div class="mb-3 mt-4">
                        <label class="form-label" for="reminder_days_before">Waktu Pengiriman Tagihan (H-X)</label>
                        <select class="form-select" id="reminder_days_before" name="reminder_days_before">
                            <option value="0" {{ old('reminder_days_before', $setting->reminder_days_before) == 0 ? 'selected' : '' }}>Hari H (Tepat saat Jatuh Tempo)</option>
                            <option value="1" {{ old('reminder_days_before', $setting->reminder_days_before) == 1 ? 'selected' : '' }}>H-1 (1 Hari sebelum Jatuh Tempo)</option>
                            <option value="2" {{ old('reminder_days_before', $setting->reminder_days_before) == 2 ? 'selected' : '' }}>H-2 (2 Hari sebelum Jatuh Tempo)</option>
                            <option value="3" {{ old('reminder_days_before', $setting->reminder_days_before) == 3 ? 'selected' : '' }}>H-3 (3 Hari sebelum Jatuh Tempo)</option>
                            <option value="7" {{ old('reminder_days_before', $setting->reminder_days_before) == 7 ? 'selected' : '' }}>H-7 (7 Hari sebelum Jatuh Tempo)</option>
                        </select>
                        <div class="form-text">
                            Pilih kapan sistem harus membuat tagihan dan mengirimkan pengingat WhatsApp kepada pelanggan. Tanggal bayar di tagihan akan tetap sama.
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
