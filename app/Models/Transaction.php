<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // Tambahkan ini, sesuaikan dengan nama tabel di DB Anda

    protected $fillable = [
        'transaction_date', // Sesuaikan dengan nama kolom di DB
        'customer_number',  // Sesuaikan dengan nama kolom di DB jika ada
        'total_amount',     // Sesuaikan dengan nama kolom di DB
        'user_id'           // Sesuaikan dengan nama kolom di DB
    ];

    protected $casts = [
        'transaction_date' => 'datetime', // Sesuaikan dengan nama kolom
        // 'items' tidak seharusnya di sini, karena detail item ada di TransactionDetail
    ];

    // Relasi: Satu transaksi memiliki banyak detail transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }

    // Relasi: Transaksi dicatat oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}