<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;                    
use App\Http\Controllers\LoginController;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Http\Controllers\HarianController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BulananController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InputTransaksiController;
use App\Http\Controllers\RekapHarianController;
use App\Http\Controllers\RekapBulananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;

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

Route::get('/dashbord', function () {
    return view('dashbord');
});

Route::get('/inputpesan', function () {
    return view('inputpesan');
});

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/transaksi/input', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('/transaksi/store', [TransactionController::class, 'store'])->name('transactions.store');

Route::get('/transaction', [HarianController::class, 'index'])->name('transaction');

Route::get('/bulanan', [BulananController::class, 'index'])->name('bulanan');

Route::get('/rekapharian', [HarianController::class, 'index'])->name('rekapharian.index');

// Rute yang hanya bisa diakses oleh 'pemilik'
Route::middleware(['auth', 'checkrole:pemilik'])->group(function () {
    Route::get('/laporan-keuangan', [LaporanController::class, 'index'])->name('laporan.keuangan');
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
});

// Rute dashboard (untuk pemilik)
Route::middleware(['auth', 'checkrole:pemilik,kasir'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rute input transaksi (untuk kasir dan pemilik)
Route::middleware(['auth', 'checkrole:pemilik,kasir'])->get('/input-transaksi', [InputTransaksiController::class, 'index'])->name('input.transaksi');

Route::get('/rekap-harian', [RHarianController::class, 'index'])->name('rekap.harian');
Route::get('/rekap-bulanan', [BulananController::class, 'index'])->name('rekap.bulanan');

// Rute login (tanpa middleware auth)
Route::get('/login', [App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'login']);