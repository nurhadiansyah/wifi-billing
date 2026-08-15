<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/kebijakan-privasi', function () {
    return view('privacy');
})->name('privacy');

Route::get('/tentang-kami', function () {
    return view('about');
})->name('about');

Route::get('/syarat-ketentuan', function () {
    return view('terms');
})->name('terms');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.pelanggan.index');
    }
    return redirect()->route('client.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ==========================================
// RUTE KHUSUS ADMIN
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // halaman data pelanggan
    Route::get('/pelanggan', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('pelanggan.index');

    // Halaman Data Pelanggan
    Route::get('/pelanggan', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/tambah', [App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('pelanggan.store');
    Route::put('/pelanggan/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('pelanggan.destroy');

    // Halaman Broadcast Gangguan
    Route::get('/broadcast', [App\Http\Controllers\Admin\BroadcastController::class, 'index'])->name('broadcast.index');
    Route::post('/broadcast/send', [App\Http\Controllers\Admin\BroadcastController::class, 'send'])->name('broadcast.send');

    // Halaman Paket WiFi
    Route::get('/paket', [App\Http\Controllers\Admin\PackageController::class, 'index'])->name('paket.index');
    Route::post('/paket', [App\Http\Controllers\Admin\PackageController::class, 'store'])->name('paket.store');
    Route::put('/paket/{id}', [App\Http\Controllers\Admin\PackageController::class, 'update'])->name('paket.update');
    Route::delete('/paket/{id}', [App\Http\Controllers\Admin\PackageController::class, 'destroy'])->name('paket.destroy');

    // Halaman Tagihan
    Route::get('/tagihan', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('tagihan.index');
    Route::post('/tagihan', [App\Http\Controllers\Admin\InvoiceController::class, 'store'])->name('tagihan.store');
    Route::put('/tagihan/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'update'])->name('tagihan.update');
    Route::put('/tagihan/{id}/lunas', [App\Http\Controllers\Admin\InvoiceController::class, 'markAsPaid'])->name('tagihan.paid');
    Route::delete('/tagihan/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'destroy'])->name('tagihan.destroy');

    // Tagihan Massal:
    Route::post('/tagihan/generate-bulk', [App\Http\Controllers\Admin\InvoiceController::class, 'generateBulk'])->name('tagihan.generateBulk');
    
    // WhatsApp Reminder:
    Route::post('/tagihan/wa-bulk', [App\Http\Controllers\Admin\InvoiceController::class, 'sendBulkWaReminder'])->name('tagihan.waBulk');
    Route::post('/tagihan/{id}/wa', [App\Http\Controllers\Admin\InvoiceController::class, 'sendWaReminder'])->name('tagihan.wa');
    
    Route::put('/tagihan/{id}/lunas', [App\Http\Controllers\Admin\InvoiceController::class, 'markAsPaid'])->name('tagihan.paid');
    Route::delete('/tagihan/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'destroy'])->name('tagihan.destroy');

    // TAMBAHKAN BARIS INI: Rute Data Laporan Pelanggan (Admin)
    Route::get('/laporan', [App\Http\Controllers\Admin\TicketController::class, 'index'])->name('laporan.index');
    Route::put('/laporan/{ticket}', [App\Http\Controllers\Admin\TicketController::class, 'update'])->name('laporan.update');
    // Rute Manajemen Teknisi (Tanpa awalan /admin ganda)
    Route::get('/teknisi', [App\Http\Controllers\Admin\TechnicianController::class, 'index'])->name('teknisi.index');
    Route::post('/teknisi', [App\Http\Controllers\Admin\TechnicianController::class, 'store'])->name('teknisi.store');
    Route::put('/teknisi/{technician}', [App\Http\Controllers\Admin\TechnicianController::class, 'update'])->name('teknisi.update');
    Route::delete('/teknisi/{technician}', [App\Http\Controllers\Admin\TechnicianController::class, 'destroy'])->name('teknisi.destroy');

    // Sesuaikan "Admin\InvoiceController" dengan nama controller tagihan untuk Admin yang Anda gunakan
    Route::post('/admin/tagihan/{id}/lunas', [App\Http\Controllers\Admin\InvoiceController::class, 'markAsPaid'])->name('admin.tagihan.lunas');
    
    // Rute untuk memproses form import Excel
    // Rute Import Excel (Cukup tulis 'pelanggan.import' karena otomatis ditambah awalan 'admin.' dari group)
    Route::post('/pelanggan/import', [App\Http\Controllers\Admin\CustomerController::class, 'import'])->name('pelanggan.import');

});

// ==========================================
// RUTE KHUSUS PELANGGAN (CLIENT)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // KATA '/dashboard' DIUBAH MENJADI '/client/dashboard'
    Route::get('/client/dashboard', function () {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.pelanggan.index');
        }

        $user->load(['package', 'invoices' => function($query) {
            $query->orderBy('due_date', 'desc');
        }]);

        return view('client.dashboard', compact('user'));
    })->name('client.dashboard');


    // Rute Halaman Tagihan Saya
    Route::get('/client/tagihan', [App\Http\Controllers\Client\InvoiceController::class, 'index'])->name('client.tagihan');

    // TAMBAHKAN BARIS INI: Rute Pusat Bantuan & Laporan
    Route::get('/client/bantuan', [App\Http\Controllers\Client\TicketController::class, 'index'])->name('client.bantuan.index');
    Route::post('/client/bantuan', [App\Http\Controllers\Client\TicketController::class, 'store'])->name('client.bantuan.store');

    // Rute Pembayaran Tripay untuk Klien
    Route::get('/tagihan/{bill}/bayar', [App\Http\Controllers\Client\PaymentController::class, 'checkout'])->name('client.payment.checkout');
    Route::post('/tagihan/{bill}/bayar', [App\Http\Controllers\Client\PaymentController::class, 'store'])->name('client.payment.store');
    Route::get('/tagihan/{bill}/instruksi', [App\Http\Controllers\Client\PaymentController::class, 'detail'])->name('client.payment.detail');

    Route::post('/tagihan/{id}/batal', [App\Http\Controllers\Client\PaymentController::class, 'cancel'])->name('client.payment.cancel');


    });

// Callback Webhook Tripay (Taruh di luar grup auth)
Route::post('/tripay/callback', [App\Http\Controllers\Client\PaymentController::class, 'callback']);

// ==========================================
// RUTE PEMBAYARAN PUBLIK (TANPA LOGIN)
// ==========================================
Route::post('/cek-tagihan', [App\Http\Controllers\PublicPaymentController::class, 'checkBill'])->name('public.check-bill.post');
Route::get('/cek-tagihan', function() { return redirect('/'); })->name('public.check-bill.get');

Route::get('/pay/{invoice_number}', [App\Http\Controllers\PublicPaymentController::class, 'checkout'])->name('public.payment.checkout');
Route::post('/pay/{invoice_number}', [App\Http\Controllers\PublicPaymentController::class, 'store'])->name('public.payment.store');