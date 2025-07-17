<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; 
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function create()
    {
        $today = Carbon::today();
        $countToday = Transaction::whereDate('created_at', $today)->count();
        $nextNumber = $countToday + 1;

        // Ambil dari session jika ada (setelah redirect)
        $nomor_pelanggan = session('nomor_pelanggan', $nextNumber);

        return view('inputpesan', compact('nomor_pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_pelanggan' => 'required|integer|min:1',
            'order_data' => 'required',
        ]);

        $items = json_decode($request->order_data, true);
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        Transaction::create([
            'nomor_pelanggan' => $request->nomor_pelanggan,
            'total' => $total,
            'items' => $items, // pastikan field items di model Transaction bertipe array/cast json
            'created_at' => now(),
        ]);

        return redirect()->route('rekapharian.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}