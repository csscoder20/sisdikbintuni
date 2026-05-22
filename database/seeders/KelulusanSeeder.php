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

                $kelasAkhir = \App\Models\Siswa::where('sekolah_id', $sekolah->id)
                    ->whereHas('rombel', fn ($query) => $query->where('tingkat', 12))
                    ->count();

                $peserta = max(50, $kelasAkhir + (($i - 3) * 7));
                $lulus = max(0, $peserta - ($sekolah->id + $i) % 8);

                $persentase = ($lulus / $peserta) * 100;

                $lanjutPT = (int) round($lulus * (0.28 + (($sekolah->id + $i) % 18) / 100));

                Kelulusan::updateOrCreate(
                    [
                        'sekolah_id' => $sekolah->id,
                        'tahun' => $tahun,
                    ],
                    [
                        'jumlah_peserta_ujian' => $peserta,
                        'jumlah_lulus' => $lulus,
                        'persentase_kelulusan' => round($persentase, 2),
                        'jumlah_lanjut_pt' => $lanjutPT,
                    ]
                );
            }
        }
    }
}
