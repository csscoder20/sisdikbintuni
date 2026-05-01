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
            'email' => 'superadmin@sisdik.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $superAdmin->assignRole('super_admin');

        // 🔹 ADMIN DINAS
        $adminDinas = User::create([
            'name' => 'Admin Dinas',
            'email' => 'dinas@sisdik.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $adminDinas->assignRole('admin_dinas');

        // 🔹 OPERATOR 1
        $op1 = User::create([
            'name' => 'Operator Sekolah 1',
            'email' => 'operator1@sisdik.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $op1->assignRole('operator');

        // 🔹 OPERATOR 2
        $op2 = User::create([
            'name' => 'Operator Sekolah 2',
            'email' => 'operator2@sisdik.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $op2->assignRole('operator');

        // 🔹 OPERATOR 3
        $op3 = User::create([
            'name' => 'Operator Sekolah 3',
            'email' => 'operator3@sisdik.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $op3->assignRole('operator');

         // 🔹 OPERATOR SMK 1 BINTUNI
        $op4 = User::create([
            'name' => 'Operator SMK Negeri 1 Bintuni',
            'email' => 'smknegeri1bintuni@gmail.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $op4->assignRole('operator');
    }
}
