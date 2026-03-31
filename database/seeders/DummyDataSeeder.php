<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\User;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Gtk;
use App\Models\GtkRiwayatPendidikan;
use App\Models\GedungRuang;
use App\Models\PeriodeLaporan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Special Schools and Operators
        $schoolsData = [
            ['nama' => 'SMA Negeri 1 Bintuni', 'email' => 'sman1bintuni@example.com'],
            ['nama' => 'SMP Negeri 2 Bintuni', 'email' => 'smpn2bintuni@example.com'],
            ['nama' => 'SD Negeri 3 Bintuni', 'email' => 'sdn3bintuni@example.com'],
        ];

        foreach ($schoolsData as $data) {
            $sekolah = Sekolah::factory()->create([
                'nama_sekolah' => $data['nama'],
                'email_sekolah' => $data['email'],
            ]);

            // Create Operator for this school
            User::factory()->create([
                'name' => 'Operator ' . $data['nama'],
                'email' => strtolower(str_replace(' ', '', $data['nama'])) . '@operator.com',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'is_verified' => true,
                'sekolah_id' => $sekolah->id,
            ]);

            // Create Rombels
            $rombels = Rombel::factory()->count(3)->create([
                'id_sekolah' => $sekolah->id,
            ]);

            foreach ($rombels as $rombel) {
                // Create Siswa in each rombel
                Siswa::factory()->count(10)->create([
                    'id_rombel' => $rombel->id,
                ]);
            }

            // Create GTK
            $gtks = Gtk::factory()->count(5)->create([
                'id_sekolah' => $sekolah->id,
            ]);

            foreach ($gtks as $gtk) {
                GtkRiwayatPendidikan::factory()->create([
                    'id_gtk' => $gtk->id,
                ]);
            }

            // Create Gedung & Ruang
            GedungRuang::factory()->count(2)->create([
                'id_sekolah' => $sekolah->id,
            ]);
        }

        // 2. Create Global Periode Laporan
        PeriodeLaporan::create(['tahun' => '2024', 'bulan' => 1]);
        PeriodeLaporan::create(['tahun' => '2024', 'bulan' => 2]);
        PeriodeLaporan::create(['tahun' => '2024', 'bulan' => 3]);
    }
}
