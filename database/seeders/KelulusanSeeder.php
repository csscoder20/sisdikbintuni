<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;
use App\Models\Kelulusan;

class KelulusanSeeder extends Seeder
{
    public function run()
    {
        $sekolahs = Sekolah::all();

        $tahunSekarang = now()->year;

        foreach ($sekolahs as $sekolah) {

            // 5 tahun terakhir
            for ($i = 5; $i >= 1; $i--) {

                $tahun = $tahunSekarang - $i;

                $peserta = rand(50, 200);

                $lulus = rand($peserta - 10, $peserta); // hampir semua lulus

                $persentase = ($lulus / $peserta) * 100;

                $lanjutPT = rand(0, $lulus);

                Kelulusan::create([
                    'sekolah_id' => $sekolah->id,
                    'tahun' => $tahun,
                    'jumlah_peserta_ujian' => $peserta,
                    'jumlah_lulus' => $lulus,
                    'persentase_kelulusan' => round($persentase, 2),
                    'jumlah_lanjut_pt' => $lanjutPT,
                ]);
            }
        }
    }
}