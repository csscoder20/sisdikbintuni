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

                $hariKerja = rand(20, 26);

                $sakit = rand(0, 2);
                $izin = rand(0, 2);
                $alfa = rand(0, 1);

                $hadir = $hariKerja - ($sakit + $izin + $alfa);

                KehadiranGtk::create([
                    'gtk_id' => $gtk->id,
                    'laporan_id' => $laporan->id,
                    'hadir' => $hadir,
                    'sakit' => $sakit,
                    'izin' => $izin,
                    'alfa' => $alfa,
                    'hari_kerja' => $hariKerja,
                ]);
            }
        }
    }
}
