<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Menu;
use App\Models\User; // Pastikan User Model di-import

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk Pendapatan Hari Ini dan Transaksi Hari Ini
        $today = date('Y-m-d'); // Menggunakan PHP Native date

        $transactionsToday = Transaction::whereDate('transaction_date', $today)->get();
        $totalIncomeToday = $transactionsToday->sum('total_amount');
        $transactionCountToday = $transactionsToday->count();

        // Data untuk Pendapatan Bulan Ini
        $startOfMonth = date('Y-m-01 00:00:00');
        $endOfMonth = date('Y-m-t 23:59:59');
        $totalIncomeMonth = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('total_amount');

        // Data untuk Menu Terlaris (Hari Ini)
        $topSellingMenuToday = TransactionDetail::selectRaw('menu_id, SUM(quantity) as total_quantity')
            ->whereHas('transaction', function($query) use ($today) {
                $query->whereDate('transaction_date', $today);
            })
            ->groupBy('menu_id')
            ->orderByDesc('total_quantity')
            ->with('menu') // Load relasi menu
            ->first();

        // Data untuk Grafik Pendapatan Per Bulan (dalam setahun berjalan)
        $currentYear = date('Y');
        $monthlyIncomeData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("{$currentYear}-{$m}-01"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("{$currentYear}-{$m}-01"));

            $income = Transaction::whereBetween('transaction_date', [$monthStart, $monthEnd])->sum('total_amount');
            $monthlyIncomeData[date('M', strtotime("{$currentYear}-{$m}-01"))] = $income;
        }

        // Data untuk Transaksi Terakhir (5 Terakhir)
        $latestTransactions = Transaction::orderBy('transaction_date', 'desc')
                                        ->with('user') // Load relasi user jika ingin menampilkan nama kasir
                                        ->take(5)
                                        ->get();

        // Kirim semua data ke view 'dashbord'
        return view('dashbord', compact(
            'totalIncomeToday',
            'transactionCountToday',
            'topSellingMenuToday',
            'totalIncomeMonth', // Variabel ini sudah pasti dihitung dan dikirim
            'monthlyIncomeData',
            'latestTransactions'
        ));
    }
}