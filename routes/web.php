<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Keranjang\IndexController as KeranjangController;
use App\Http\Controllers\Meja\IndexController as MejaController;
use App\Http\Controllers\Pembayaran\IndexController as PembayaranController;
use App\Http\Controllers\Produk\IndexController as ProdukController;
use App\Http\Controllers\Riwayat\IndexController as RiwayatController;
use App\Http\Controllers\Transaksi\IndexController as TransaksiController;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function() {
    Route::controller(ProdukController::class)->group(function() {
        Route::get('/renderProduk', 'renderJson');
        Route::post('/addToCart', 'addToCart');
        Route::post('/deleteFromCart', 'deleteCart');
        Route::get('/searchProduk/{keyword_search}', 'searchProduk');
        Route::get('/filterKategori/{key_kategori}', 'filterKategori');
    });
    Route::controller(KeranjangController::class)->group(function () {
        Route::get('/renderKeranjang', 'renderJson');
    });
    Route::controller(PembayaranController::class)->group(function () {
        Route::get('/renderTotalPembayaran', 'renderJson');
        Route::post('/addPesananModal', 'addPesanModal');
        Route::post('/kurangPesananModal', 'kurangPesanModal');
    });
    Route::controller(MejaController::class)->group(function () {
        Route::get('/renderMeja', 'renderJson');
    });
    Route::controller(TransaksiController::class)->group(function () {
        Route::post('/addTransaksi', 'addTransaksi');
    });
    Route::controller(RiwayatController::class)->group(function() {
        Route::get('/renderRiwayatTransaksi', 'renderJson');
    });

    Route::post('/logout', [LogoutController::class, 'destroySession']);
    Route::get('/', function () {
        return view('welcome');
    });
});


Route::middleware(['guest'])
    ->controller(LoginController::class)
    ->group(function() {
        Route::get('/login', 'render');
        Route::post('/login', 'login')->name('login');
});
