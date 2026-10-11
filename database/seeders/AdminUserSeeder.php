<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'chepitimayassecurite@gmail.com'],
            [
                'name' => 'Admin Chepitimayas Sécurité',
                'password' => Hash::make('Password123'),
            ]
        );
    }
}