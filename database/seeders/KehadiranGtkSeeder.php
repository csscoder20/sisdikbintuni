<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\Gtk;
use App\Models\KehadiranGtk;

class KehadiranGtkSeeder extends Seeder
{
    public function run()
    {
        $laporans = Laporan::all();

        foreach ($laporans as $laporan) {

            $gtks = Gtk::where('sekolah_id', $laporan->sekolah_id)->get();

            foreach ($gtks as $gtk) {
                $seed = $gtk->id + ($laporan->bulan * 31);
                // Add per-school offset (~20) to work days
                $schoolIndex = $gtk->sekolah_id;
                $offset = ($schoolIndex % 5) * 5 + 10; // creates gaps of ~10-30 days
                $hariKerja = 22 + ($seed % 5) + $offset;
                $sakit = $seed % 7 === 0 ? 2 : ($seed % 5 === 0 ? 1 : 0);
                $izin = $seed % 6 === 0 ? 1 : 0;
                $alfa = $seed % 19 === 0 ? 1 : 0;
                $hadir = max(0, $hariKerja - ($sakit + $izin + $alfa));
                $dataHarian = [];

                for ($hari = 1; $hari <= $hariKerja; $hari++) {
                    $dataHarian[$hari] = 'H';
                }

                if ($sakit > 0) {
                    $dataHarian[3 + ($seed % 9)] = 'S';
                }

                if ($sakit > 1) {
                    $dataHarian[10 + ($seed % 8)] = 'S';
                }

                if ($izin > 0) {
                    $dataHarian[6 + ($seed % 11)] = 'I';
                }

                if ($alfa > 0) {
                    $dataHarian[14 + ($seed % 7)] = 'A';
                }

                KehadiranGtk::updateOrCreate(
                    [
                        'gtk_id' => $gtk->id,
                        'laporan_id' => $laporan->id,
                    ],
                    [
                        'bulan' => $laporan->bulan,
                        'tahun' => $laporan->tahun,
                        'data_harian' => json_encode($dataHarian),
                        'hadir' => $hadir,
                        'sakit' => $sakit,
                        'izin' => $izin,
                        'alfa' => $alfa,
                        'hari_kerja' => $hariKerja,
                    ]
                );
            }
        }
    }
}
