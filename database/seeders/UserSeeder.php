<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tài khoản Admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'phone' => '0123456789',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // Tài khoản Người dùng
        User::updateOrCreate(
            ['email' => 'trantungvn25@gmail.com'],
            [
                'name' => 'Trần Tùng',
                'password' => Hash::make('123456'),
                'phone' => '0987654321',
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
