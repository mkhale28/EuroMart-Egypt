<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@euromartegypt.com',
            'password' => Hash::make('Admin@2024'),
            'role' => 'admin',
            'phone' => '01000000000',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Staff Admin',
            'email' => 'staff@euromartegypt.com',
            'password' => Hash::make('Staff@2024'),
            'role' => 'admin',
            'phone' => '01000000001',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
    }
}
