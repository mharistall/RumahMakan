<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk menggunakan DB Facade

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menonaktifkan pemeriksaan foreign key sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // <<< PERBAIKAN: Tambahkan ini

        // Menghapus data yang ada sebelum seeding
        Category::truncate();

        // Mengaktifkan kembali pemeriksaan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // <<< PERBAIKAN: Tambahkan ini

        // Data kategori yang akan dimasukkan
        $categories = [
            ['name' => 'Nasi'],
            ['name' => 'Lauk Pauk'],
            ['name' => 'Sayuran'],
            ['name' => 'Minuman'],
            ['name' => 'Cemilan'],
        ];

        // Masukkan data ke tabel 'kategorimenu'
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}