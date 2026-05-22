<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gtk;
use App\Models\Mengajar;

class MengajarSeeder extends Seeder
{
    public function run(): void
    {
        // Retrieve all GTK records with their school relation
        $gtks = Gtk::with('sekolah')->get();

        foreach ($gtks as $gtk) {
            // Create a minimal Mengajar entry so the GTK name is displayed in the Jam Mengajar table
            Mengajar::firstOrCreate([
                'gtk_id' => $gtk->id,
            ]);
        }
    }
}
?>
