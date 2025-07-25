<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu'; // Sesuaikan dengan nama tabel di DB Anda

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image'
    ];

    // Relasi: Satu menu memiliki satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // Relasi: Satu menu bisa ada di banyak detail transaksi
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'menu_id', 'id');
    }
}