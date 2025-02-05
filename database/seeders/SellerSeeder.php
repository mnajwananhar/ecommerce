<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SellerSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Test Seller',
            'email' => 'seller@example.com',
            'password' => Hash::make('password'), // Password default
            'role' => 'seller', // Pastikan role adalah 'seller'
        ]);
    }
}
