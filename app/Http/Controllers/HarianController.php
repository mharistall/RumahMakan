<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Menu;
use App\Models\Category;

class HarianController extends Controller
{
    public function index(Request $request)
    {
        // a. Ambil tanggal dari request, default hari ini (PHP Native)
        $selectedDate = $request->input('date', date('Y-m-d'));
        $perPage = $request->input('per_page', 5);

        // b. Mengambil transaksi untuk tanggal tersebut dengan pagination
        $transactions = Transaction::whereDate('transaction_date', $selectedDate)
                                    ->orderBy('transaction_date', 'desc')
                                    ->with('details.menu')
                                    ->paginate($perPage);

        // Hitung total pemasukan hari ini
        $totalIncome = $transactions->sum('total_amount');
        $transactionCount = $transactions->total();

        // f. Menampilkan kotak menu terlaris per harinya
        $topSellingMenusPerCategory = [];
        $categories = Category::all();

        foreach ($categories as $category) {
            $topMenu = TransactionDetail::selectRaw('menu.name, SUM(detailtransaksi.quantity) as total_quantity')
                ->join('menu', 'detailtransaksi.menu_id', '=', 'menu.id')
                ->join('transaksi', 'detailtransaksi.transaction_id', '=', 'transaksi.id')
                ->whereDate('transaksi.transaction_date', $selectedDate) // Filter harian
                ->where('menu.category_id', $category->id)
                ->groupBy('menu.name')
                ->orderByDesc('total_quantity')
                ->first();

            if ($topMenu) {
                $topSellingMenusPerCategory[] = [
                    'category_name' => $category->name,
                    'menu_name' => $topMenu->name,
                    'total_quantity' => $topMenu->total_quantity,
                ];
            }
        }

        // Data untuk Grafik Pendapatan Per Bulan (untuk halaman grafik terpisah)
        $selectedYearForGraph = date('Y', strtotime($selectedDate)); // Ambil tahun dari selectedDate
        $monthlyIncomeData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("{$selectedYearForGraph}-{$m}-01"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("{$selectedYearForGraph}-{$m}-01"));

            $income = Transaction::whereBetween('transaction_date', [$monthStart, $monthEnd])->sum('total_amount');
            $monthlyIncomeData[date('M', strtotime("{$selectedYearForGraph}-{$m}-01"))] = $income;
        }

        return view('harian', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'transactionCount' => $transactionCount,
            'selectedDate' => $selectedDate,
            'topSellingMenusPerCategory' => $topSellingMenusPerCategory,
            'perPage' => $perPage,
            'monthlyIncomeData' => $monthlyIncomeData
        ]);
    }

    // Method untuk menampilkan halaman grafik harian (yang diminta sebelumnya)
    public function showGraph(Request $request, $yearSegment = null, $monthSegment = null) // Ubah nama parameter untuk kejelasan
    {
        // PERBAIKAN DI SINI: Prioritaskan input dari request (form GET)
        $selectedYear = $request->input('year', $yearSegment ?? date('Y'));
        $selectedMonth = $request->input('month', $monthSegment ?? date('n'));

        // Tentukan tanggal awal dan akhir bulan yang dipilih
        $startOfMonth = date('Y-m-01 00:00:00', strtotime("{$selectedYear}-{$selectedMonth}-01"));
        $endOfMonth = date('Y-m-t 23:59:59', strtotime("{$selectedYear}-{$selectedMonth}-01"));

        // Data untuk Grafik Pendapatan Harian (dalam bulan yang dipilih)
        $dailyIncomeData = [];
        $daysInMonth = date('t', strtotime("{$selectedYear}-{$selectedMonth}-01"));

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $currentDate = date('Y-m-d', strtotime("{$selectedYear}-{$selectedMonth}-{$d}"));
            $income = Transaction::whereDate('transaction_date', $currentDate)->sum('total_amount');
            $dailyIncomeData[$d] = $income;
        }

        // Kirim data ke view grafik harian
        return view('grafikharian', [ // Menggunakan 'grafikharian' sesuai konfirmasi Anda
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'dailyIncomeData' => $dailyIncomeData
        ]);
    }
}