<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
// use Carbon\Carbon; // Hapus jika tidak digunakan di Controller ini
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function create()
    {
        // Ambil semua kategori untuk dropdown filter dan grouping
        $categories = Category::all();

        // Ambil semua menu dengan relasi kategori
        $menus = Menu::with('category')->get();

        // Kelompokkan menu berdasarkan category_id
        $groupedMenus = $menus->groupBy('category_id');

        // Generate nomor pelanggan otomatis
        $today = date('Y-m-d');
        $countToday = Transaction::whereDate('transaction_date', $today)->count();
        $nextNumber = $countToday + 1;
        $nomor_pelanggan = session('customer_number', 'CST' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT));

        // Kirim data ke view inputpesan.blade.php
        return view('inputpesan', compact('categories', 'groupedMenus', 'nomor_pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_number' => 'required|string|max:50',
            'order_data' => 'required|json',
        ]);

        $items = json_decode($request->order_data, true);
        $totalTransactionAmount = 0;

        foreach ($items as $item) {
            $totalTransactionAmount += ($item['price'] * $item['quantity']);
        }

        $transaction = Transaction::create([
            'transaction_date' => date('Y-m-d H:i:s'),
            'customer_number' => $request->customer_number,
            'total_amount' => $totalTransactionAmount,
            'user_id' => Auth::id(),
        ]);

        foreach ($items as $item) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'subtotal' => ($item['price'] * $item['quantity']),
            ]);
        }

        // <<< PERUBAHAN DI SINI: Kembali ke halaman input.transaksi >>>
        return redirect()->route('input.transaksi')->with('success', 'Transaksi berhasil disimpan!');
    }
}