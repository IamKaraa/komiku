<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Creator',
            'email' => 'creator@komiku.com',
            'password' => Hash::make('creator123'),
            'role' => 'creator',
            'status' => 'active',
            'birth_date' => '1995-05-15',
            'email_verified_at' => now(),
        ]);
    }
}
