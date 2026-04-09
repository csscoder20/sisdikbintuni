<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gtk;
use App\Models\GtkKeuangan;

class GtkKeuanganSeeder extends Seeder
{
    public function run()
    {
        $banks = ['BRI', 'BNI', 'Mandiri', 'BCA'];

        $gtks = Gtk::all();

        foreach ($gtks as $gtk) {

            // Tidak semua GTK punya data keuangan (biar realistis)
            if (rand(0, 1)) {

                GtkKeuangan::create([
                    'gtk_id' => $gtk->id,
                    'nomor_rekening' => '10' . rand(10000000, 99999999),
                    'nama_bank' => $banks[array_rand($banks)],
                    'npwp' => rand(10,99) . '.' . rand(100,999) . '.' . rand(100,999) . '.' . rand(1,9) . '-' . rand(100,999) . '.' . rand(100,999),
                ]);

            }
        }
    }
}
