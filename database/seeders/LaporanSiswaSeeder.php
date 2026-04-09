<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laporan;
use App\Models\Rombel;
use App\Models\LaporanSiswa;

class LaporanSiswaSeeder extends Seeder
{
    public function run()
    {
        $laporans = Laporan::all();

        foreach ($laporans as $laporan) {

            // ambil rombel sesuai sekolah
            $rombels = Rombel::where('sekolah_id', $laporan->sekolah_id)->get();

            foreach ($rombels as $rombel) {

                LaporanSiswa::create([
                    'laporan_id' => $laporan->id,
                    'rombel_id' => $rombel->id,
                ]);
            }
        }
    }
}
