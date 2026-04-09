<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\LaporanGtk;

class LaporanGtkSeeder extends Seeder
{
    public function run()
    {
        $laporans = Laporan::all();

        $jenisGtk = ['kepala_sekolah','guru','tenaga_administrasi'];

        foreach ($laporans as $laporan) {

            foreach ($jenisGtk as $jenis) {

                LaporanGtk::create([
                    'laporan_id' => $laporan->id,
                    'jenis_gtk' => $jenis,
                ]);
            }
        }
    }
}