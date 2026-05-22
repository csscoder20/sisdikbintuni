<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;
use App\Models\Rombel;

class RombelSeeder extends Seeder
{
    public function run()
    {
        $sekolahs = Sekolah::all();

        foreach ($sekolahs as $sekolah) {
            $rombels = $sekolah->jenjang === 'smk'
                ? [
                    ['X TKJ 1', 10], ['X AKL 1', 10], ['X OTKP 1', 10], ['X TBSM 1', 10],
                    ['XI TKJ 1', 11], ['XI AKL 1', 11], ['XI OTKP 1', 11], ['XI TBSM 1', 11],
                    ['XII TKJ 1', 12], ['XII AKL 1', 12], ['XII OTKP 1', 12], ['XII TBSM 1', 12],
                ]
                : [
                    ['X MIPA 1', 10], ['X MIPA 2', 10], ['X IPS 1', 10], ['X Bahasa', 10],
                    ['XI MIPA 1', 11], ['XI MIPA 2', 11], ['XI IPS 1', 11], ['XI Bahasa', 11],
                    ['XII MIPA 1', 12], ['XII MIPA 2', 12], ['XII IPS 1', 12], ['XII Bahasa', 12],
                ];

            foreach ($rombels as [$nama, $tingkat]) {
                Rombel::updateOrCreate(
                    [
                        'sekolah_id' => $sekolah->id,
                        'nama' => $nama,
                    ],
                    [
                        'tingkat' => $tingkat,
                    ]
                );
            }
        }
    }
}
