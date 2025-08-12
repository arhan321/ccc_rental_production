<?php

use Livewire\Livewire;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\RegisterController;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/
Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/ajukan-kostum', [FrontendController::class, 'showFormPengajuan'])->name('form.pengajuan');

Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/checkout/status', [OrderController::class, 'checkoutStatus'])
      ->name('checkout.status');



Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
     Route::get('/pengembalian', [\App\Http\Controllers\PengembalianController::class, 'index'])->name('pengembalian.index');
Route::get('/histori', [OrderController::class, 'history'])->name('frontend.histori');
    // route lainnya jika perlu
     // lihat profil
    Route::get('/profile',        [ProfileController::class, 'show' ])->name('profile.show');
    // form edit
    Route::get('/profile/edit',   [ProfileController::class, 'edit' ])->name('profile.edit');
    // proses simpan
    Route::put('/profile',        [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/pay-again/{order}',  [OrderController::class, 'payAgain'])
         ->name('orders.payAgain');
    Route::get('/riwayat-custom', [FrontendController::class, 'riwayatCustom'])->name('riwayat.custom');

    Route::post('/ajukan-kostum', [FrontendController::class, 'storePengajuanKostum'])->name('custom-request.store');
});