<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Asumsi model Transaction sudah ada
use App\Models\TransactionDetail; // Asumsi model TransactionDetail sudah ada
use App\Models\Category; // Asumsi model Category sudah ada
use Carbon\Carbon; // Digunakan untuk manipulasi tanggal

class BulananController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan bulan dan tahun dari request, default ke bulan dan tahun saat ini
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Membuat objek Carbon untuk awal dan akhir bulan yang dipilih
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->startOfDay();
        $endOfMonth = Carbon::create($selectedYear, $selectedMonth, 1)->endOfMonth()->endOfDay();

        // 1. Mengambil ringkasan pendapatan per hari dalam bulan yang dipilih
        $dailyRevenues = Transaction::selectRaw('DATE(transaction_date) as date, SUM(total_amount) as total_daily_revenue')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Mengisi tanggal yang tidak ada transaksi dengan 0
        $currentDate = $startOfMonth->copy();
        $formattedDailyRevenues = [];
        while ($currentDate->lte($endOfMonth)) {
            $dateString = $currentDate->toDateString();
            $found = false;
            foreach ($dailyRevenues as $daily) {
                if ($daily->date === $dateString) {
                    $formattedDailyRevenues[] = ['date' => $daily->date, 'total_daily_revenue' => $daily->total_daily_revenue];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $formattedDailyRevenues[] = ['date' => $dateString, 'total_daily_revenue' => 0];
            }
            $currentDate->addDay();
        }


        // 2. Menghitung total pemasukan untuk bulan yang dipilih
        $totalMonthlyRevenue = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                                        ->sum('total_amount');

        // 3. Mengambil ringkasan penjualan per kategori menu
        $categorySales = TransactionDetail::join('menus', 'transaction_details.menu_id', '=', 'menus.id')
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->selectRaw('categories.name as category_name, SUM(transaction_details.subtotal) as total_category_revenue')
            ->whereBetween('transactions.transaction_date', [$startOfMonth, $endOfMonth])
            ->groupBy('categories.name')
            ->get();

        // Mengirimkan data ke view
        return view('bulanan', compact(
            'selectedMonth',
            'selectedYear',
            'dailyRevenues',
            'totalMonthlyRevenue',
            'categorySales',
            'formattedDailyRevenues' // Gunakan ini untuk tampilan per hari yang lengkap
        ));
    }
}