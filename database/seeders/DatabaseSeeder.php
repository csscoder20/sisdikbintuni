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
       
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SekolahSeeder::class,
            OperatorSekolahSeeder::class,
            RombelSeeder::class,
            GtkSeeder::class,
            GtkPendidikanSeeder::class,
            GtkKeuanganSeeder::class,
            SiswaSeeder::class,
            LaporanSeeder::class,
            LaporanGedungSeeder::class,
            LaporanSiswaSeeder::class,
            LaporanSiswaRekapSeeder::class,
            LaporanSiswaKategoriSeeder::class,
            LaporanGtkSeeder::class,
            LaporanGtkKategoriSeeder::class,
            MengajarSeeder::class,
            KehadiranGtkSeeder::class,
            KelulusanSeeder::class,
        ]);
    }
}