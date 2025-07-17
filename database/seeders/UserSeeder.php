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
            ['email' => 'admin@rumahmakan.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@rumahmakan.com'],
            [
                'name' => 'kasir',
                'password' => Hash::make('kasir123'),
            ]
        );
    }
}