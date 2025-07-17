<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class HarianController extends Controller
{
    public function index(Request $request)
    {       
        // Ambil tanggal dari request, default hari ini
        $date = $request->input('date', Carbon::today()->toDateString());

        // Ambil transaksi untuk tanggal tersebut
        $transactions = Transaction::whereDate('created_at', $date)->get();

        // Hitung total pemasukan
        $totalIncome = $transactions->sum('total');
        $transactionCount = $transactions->count();

        // Kirim data ke blade
        return view('rekapharian', [
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'transactionCount' => $transactionCount,
            'selectedDate' => $date,
        ]);
    }
}