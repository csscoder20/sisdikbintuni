<?php

namespace Database\Seeders;

use App\Models\Mapel;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = database_path('seeders/data/ref_mapel.csv');
        
        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found at: {$csvFile}");
            return;
        }

        $schools = Sekolah::all();
        
        if ($schools->isEmpty()) {
            $this->command->warn("No schools found. Skipping Mapel seeding.");
            return;
        }

        $handle = fopen($csvFile, 'r');
        $header = fgetcsv($handle, 0, ';'); // Read header

        $mapelData = [];
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 5) continue;
            
            $mapelData[] = [
                'kode' => $row[0],
                'nama' => $row[1],
                'jenjang' => $row[2],
                'jjp' => str_replace(',', '.', $row[3]),
                'tingkat' => $row[4],
            ];
        }
        fclose($handle);

        $this->command->info("Seeding subjects for " . $schools->count() . " schools...");

        DB::transaction(function () use ($schools, $mapelData) {
            foreach ($schools as $sekolah) {
                foreach ($mapelData as $data) {
                    Mapel::updateOrCreate(
                        [
                            'sekolah_id' => $sekolah->id,
                            'kode_mapel' => $data['kode'],
                        ],
                        [
                            'nama_mapel' => $data['nama'],
                            'jjp' => $data['jjp'],
                            'jenjang' => $data['jenjang'],
                            'tingkat' => $data['tingkat'],
                        ]
                    );
                }
            }
        });

        $this->command->info("Mapel seeding completed successfully.");
    }
}
