<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HarianController; // Untuk Rekap Harian
use App\Http\Controllers\BulananController; // Untuk Rekap Bulanan
use App\Http\Middleware\CheckRole; // <<< PERBAIKAN: Import CheckRole middleware
use App\Http\Controllers\MenuController;
// Hapus import Controller yang tidak digunakan lagi (misalnya App\Models\Transaction; Carbon\Carbon;
// karena sudah ada di dalam Controller atau tidak diperlukan di web.php langsung)
// Hapus juga App\Http\Controllers\InputTransaksiController; RekapHarianController; RekapBulananController;
// dan LaporanController; PengaturanController; jika Anda belum membuatnya atau menggunakan nama lain.
// Jika LaporanController dan PengaturanController akan dibuat, biarkan saja import-nya.

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Rute Halaman Publik (tanpa login) ---

// Halaman awal, akan mengarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Login dan Logout
// Pastikan LoginController Anda memiliki method 'showLogin' (GET) dan 'login' (POST)
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- Rute yang Memerlukan Autentikasi ---
// Semua rute di dalam grup ini akan memerlukan user untuk login terlebih dahulu.
Route::middleware(['auth'])->group(function () {

    // Rute Dashboard (akses oleh pemilik dan admin)
    // Pastikan DashboardController::index mengarahkan ke view 'dashbord'
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')
        ->middleware(CheckRole::class . ':pemilik');  // Menggunakan 'admin' sebagai ganti 'kasir'

    // Rute Input Transaksi (akses hanya oleh admin)
    Route::get('/input-transaksi', [TransactionController::class, 'create'])->name('input.transaksi')
        ->middleware(CheckRole::class . ':admin');
    Route::post('/transaksi/store', [TransactionController::class, 'store'])->name('transactions.store')
        ->middleware(CheckRole::class . ':admin');

    // Rute Rekap Harian (akses oleh pemilik )
    // Menggunakan HarianController::index
    Route::get('/harian', [HarianController::class, 'index'])->name('rekap.harian')
        ->middleware(CheckRole::class . ':pemilik');
// <<< Rute BARU untuk Halaman Grafik Harian >>>
    Route::get('/rekap-harian/grafik/{year?}/{month?}', [App\Http\Controllers\HarianController::class, 'showGraph'])->name('rekap.harian.grafik')
        ->middleware(App\Http\Middleware\CheckRole::class . ':pemilik'); // Asumsi hanya pemilik yang bisa melihat grafik ini

    // Rute Rekap Bulanan (akses oleh pemilik)
    // Menggunakan BulananController::index
    Route::get('/bulanan', [BulananController::class, 'index'])->name('rekap.bulanan')
        ->middleware(CheckRole::class . ':pemilik');
        // Rute BARU untuk Halaman Grafik Bulanan
    Route::get('/rekap-bulanan/grafik/{year?}', [BulananController::class, 'showGraph'])->name('rekap.bulanan.grafik')
        ->middleware(CheckRole::class . ':pemilik'); // Hanya pemilik
    
    // Rute yang hanya bisa diakses oleh 'pemilik'
    // Asumsi Anda akan membuat LaporanController dan PengaturanController
    // Jika belum ada, Anda perlu membuat controllernya:
    // php artisan make:controller LaporanController
    // php artisan make:controller PengaturanController
    // Lalu isi method 'index' di dalamnya.

    // Rute Edit Menu (akses hanya oleh admin)
    Route::get('/edit-menu', [MenuController::class, 'index'])->name('menu.index')
        ->middleware(CheckRole::class . ':admin');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store')
        ->middleware(CheckRole::class . ':admin');
    Route::get('/edit-menu', [MenuController::class, 'index'])->name('menu.index')
        ->middleware(CheckRole::class . ':admin');
    Route::post('/menu/store', [MenuController::class, 'store'])->name('menu.store')
        ->middleware(CheckRole::class . ':admin');
    Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update') // Rute untuk update
        ->middleware(CheckRole::class . ':admin');
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy') // Rute untuk hapus
        ->middleware(CheckRole::class . ':admin');

    
});

