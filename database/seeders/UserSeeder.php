<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 🔹 SUPER ADMIN
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@dikporabintuni.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $superAdmin->assignRole('super_admin');

        // 🔹 ADMIN DINAS
        $adminDinas = User::create([
            'name' => 'Admin Dinas',
            'email' => 'dinas@dikporabintuni.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $adminDinas->assignRole('admin_dinas');
    }
}