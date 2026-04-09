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

            // Kelas X
            Rombel::create([
                'sekolah_id' => $sekolah->id,
                'nama' => 'Kelas X IPA 1',
                'tingkat' => 10,
            ]);

            Rombel::create([
                'sekolah_id' => $sekolah->id,
                'nama' => 'Kelas X IPS 1',
                'tingkat' => 10,
            ]);

            // Kelas XI
            Rombel::create([
                'sekolah_id' => $sekolah->id,
                'nama' => 'Kelas XI IPA 1',
                'tingkat' => 11,
            ]);

            Rombel::create([
                'sekolah_id' => $sekolah->id,
                'nama' => 'Kelas XI IPS 1',
                'tingkat' => 11,
            ]);

            // Kelas XII
            Rombel::create([
                'sekolah_id' => $sekolah->id,
                'nama' => 'Kelas XII IPA 1',
                'tingkat' => 12,
            ]);

            Rombel::create([
                'sekolah_id' => $sekolah->id,
                'nama' => 'Kelas XII IPS 1',
                'tingkat' => 12,
            ]);
        }
    }
}