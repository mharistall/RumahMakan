<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['username' => 'pemilik@rumahmakan.com'],
            [
                'name' => 'pemilik',
                'password' => Hash::make('pemilik123'),
                'role' => 'pemilik',
            ]
        );

        User::updateOrCreate(
            ['username' => 'admin@rumahmakan.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin', // atau 'admin' jika ingin role baru
            ]
        );
    }
}