<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Laporan;

class LaporanSeeder extends Seeder
{
    public function run()
    {
        $sekolahs = Sekolah::all();

        // ambil admin dinas untuk verifikasi
        $adminDinas = User::role('admin_dinas')->first();

        foreach ($sekolahs as $sekolah) {

            // bulan 1 - draft
            Laporan::create([
                'sekolah_id' => $sekolah->id,
                'bulan' => 1,
                'tahun' => 2026,
                'status' => 'draft',
            ]);

            // bulan 2 - submitted
            Laporan::create([
                'sekolah_id' => $sekolah->id,
                'bulan' => 2,
                'tahun' => 2026,
                'status' => 'submitted',
                'tanggal_submit' => now()->subDays(rand(5, 20)),
            ]);

            // bulan 3 - verified
            Laporan::create([
                'sekolah_id' => $sekolah->id,
                'bulan' => 3,
                'tahun' => 2026,
                'status' => 'verified',
                'tanggal_submit' => now()->subDays(rand(10, 30)),
                'verified_at' => now()->subDays(rand(1, 5)),
                'verified_by' => $adminDinas?->id,
            ]);
        }
    }
}