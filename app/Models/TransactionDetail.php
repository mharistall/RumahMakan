<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'detailtransaksi'; // Perbaiki ini agar sesuai dengan DB Anda

    protected $fillable = [ // Tambahkan fillable untuk kolom yang akan diisi massal
        'transaction_id',
        'menu_id',
        'quantity',
        'subtotal'
    ];

    // Relasi: Detail transaksi ini milik satu transaksi
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    // Relasi: Detail transaksi ini untuk satu menu
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }
}