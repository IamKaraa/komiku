<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@komiku.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'birth_date' => '1990-01-01',
            'email_verified_at' => now(),
        ]);
    }
}
