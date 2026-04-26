<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gtk;
use App\Models\Rombel;
use App\Models\Mengajar;
use App\Models\Mapel;

class MengajarSeeder extends Seeder
{
    public function run(): void
    {
        // ambil hanya guru
        $gurus = Gtk::with('sekolah')->where('jenis_gtk', 'Guru')->get();

        foreach ($gurus as $guru) {

            // rombel sesuai sekolah guru
            $rombels = Rombel::where('sekolah_id', $guru->sekolah_id)->get();

            if ($rombels->isEmpty()) {
                continue;
            }

            // tiap guru mengajar 1–3 rombel
            $selectedRombels = $rombels->random(min(3, $rombels->count()));

            foreach ($selectedRombels as $rombel) {

                // ambil mapel sesuai jenjang sekolah & tingkat rombel
                $mapel = Mapel::where('jenjang', $guru->sekolah->jenjang)
                    ->where('tingkat', $rombel->tingkat)
                    ->inRandomOrder()
                    ->first();

                if (!$mapel) {
                    continue;
                }

                // hindari duplicate karena ada unique constraint
                $exists = Mengajar::where('gtk_id', $guru->id)
                    ->where('rombel_id', $rombel->id)
                    ->where('mapel_id', $mapel->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                Mengajar::create([
                    'gtk_id' => $guru->id,
                    'rombel_id' => $rombel->id,
                    'mapel_id' => $mapel->id,
                    'jumlah_jam' => rand(2, 6),
                    'semester' => collect(['ganjil', 'genap'])->random(),
                    'tahun_ajaran' => now()->year . '/' . (now()->year + 1),
                    'laporan_id' => null,
                ]);
            }
        }
    }
}
