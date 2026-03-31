<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@sisdik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
        ]);

        // Dummy Data for Schools, Operators, students, etc.
        $this->call([
            DummyDataSeeder::class,
            SekolahSeeder::class,
        ]);
    }
}
