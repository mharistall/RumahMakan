<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use app\Models\Menu;

class Category extends Model
{
    protected $table = 'kategorimenu'; // Tambahkan ini
    protected $fillable = ['name'];

    // Tambahkan relasi jika diperlukan (misalnya, ke Menu)
    public function menus()
    {
        return $this->hasMany(Menu::class, 'category_id', 'id');
    }
}