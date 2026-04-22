<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/admin/dashboard', function (){
    return view('admin.dashboard');
});
require __DIR__.'/auth.php';

use App\Http\Controllers\Admin\{
    DashboardController,
    KategoriController,
    ProdukController,
    PenjualanController,
    PengeluaranController,
    PemasukanController,
    KasirController
};

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Kategori
        Route::get('/kategori', [KategoriController::class, 'index'])
            ->name('kategori');
        Route::post('/kategori', [KategoriController::class, 'store'])
            ->name('kategori.store');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])
            ->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])
            ->name('kategori.destroy');

        // Produk
        Route::get('/produk', [ProdukController::class, 'index'])
            ->name('produk');
        Route::post('/produk', [ProdukController::class, 'store'])
            ->name('produk.store');
        Route::put('/produk/{produk}', [ProdukController::class, 'update'])
            ->name('produk.update');
        Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])
            ->name('produk.destroy');

        // Penjualan & Finansial
        Route::get('/penjualan', [PenjualanController::class, 'index'])
            ->name('penjualan');
        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])
            ->name('pengeluaran');
        Route::get('/pemasukan', [PemasukanController::class, 'index'])
            ->name('pemasukan');

        //pengeluaran
        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])
            ->name('pengeluaran');

        Route::post('/pengeluaran', [PengeluaranController::class, 'store'])
            ->name('pengeluaran.store');

        Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])
            ->name('pengeluaran.destroy');

        // Manajemen Kasir
        Route::get('/kasir', [KasirController::class, 'index'])
            ->name('kasir');
        Route::post('/kasir', [KasirController::class, 'store'])
            ->name('kasir.store');
        Route::put('/kasir/{user}', [KasirController::class, 'update'])
            ->name('kasir.update');
        Route::delete('/kasir/{user}', [KasirController::class, 'destroy'])
            ->name('kasir.destroy');
    });


use App\Http\Controllers\Kasir\SaleController;

Route::prefix('kasir')
    ->name('kasir.')
    ->middleware(['auth', 'role:kasir'])
    ->group(function () {

        Route::get('/sale', [SaleController::class, 'index'])
            ->name('sale');

        Route::post('/transaksi', [SaleController::class, 'store'])
            ->name('transaksi.store');
});
