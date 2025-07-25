<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Category;

class BulananController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', date('n'));
        $selectedYear = $request->input('year', date('Y'));
        $perPage = $request->input('per_page', 5);

        $startOfMonth = date('Y-m-01 00:00:00', strtotime("{$selectedYear}-{$selectedMonth}-01"));
        $endOfMonth = date('Y-m-t 23:59:59', strtotime("{$selectedYear}-{$selectedMonth}-01"));

        $dailySummaries = Transaction::selectRaw('DATE(transaction_date) as transaction_date_only, COUNT(DISTINCT customer_number) as total_customers_today, SUM(total_amount) as total_daily_income')
                                    ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                                    ->groupBy('transaction_date_only')
                                    ->orderBy('transaction_date_only', 'desc')
                                    ->paginate($perPage);

        $totalMonthlyIncome = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                                        ->sum('total_amount');
        $totalMonthlyTransactions = Transaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->count();

        $topSellingMenusPerCategory = [];
        $categories = Category::all();

        foreach ($categories as $category) {
            $topMenu = TransactionDetail::selectRaw('menu.name, SUM(detailtransaksi.quantity) as total_quantity')
                ->join('menu', 'detailtransaksi.menu_id', '=', 'menu.id')
                ->join('transaksi', 'detailtransaksi.transaction_id', '=', 'transaksi.id')
                ->whereBetween('transaksi.transaction_date', [$startOfMonth, $endOfMonth])
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

        return view('bulanan', [
            'dailySummaries' => $dailySummaries,
            'totalMonthlyIncome' => $totalMonthlyIncome,
            'totalMonthlyTransactions' => $totalMonthlyTransactions,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'perPage' => $perPage,
            'topSellingMenusPerCategory' => $topSellingMenusPerCategory,
        ]);
    }

    public function showGraph(Request $request, $year = null)
    {
        $selectedYear = $request->input('year', $year ?? date('Y'));

        $monthlyIncomeData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("{$selectedYear}-{$m}-01"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("{$selectedYear}-{$m}-01"));

            $income = Transaction::whereBetween('transaction_date', [$monthStart, $monthEnd])->sum('total_amount');
            $monthlyIncomeData[date('M', strtotime("{$selectedYear}-{$m}-01"))] = $income;
        }

        return view('grafikbulanan', [
            'selectedYear' => $selectedYear,
            'monthlyIncomeData' => $monthlyIncomeData
        ]);
    }
}