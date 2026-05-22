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
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@dikporabintuni.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $superAdmin->assignRole('super_admin');

        // 🔹 ADMIN DINAS
        $adminDinas = User::updateOrCreate(
            ['email' => 'dinas@dikporabintuni.com'],
            [
                'name' => 'Admin Dinas',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        $adminDinas->assignRole('admin_dinas');
    }
}
