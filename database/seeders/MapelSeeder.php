<?php

namespace Database\Seeders;

use App\Models\Mapel;
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

        $handle = fopen($csvFile, 'r');
        $header = fgetcsv($handle, 0, ';'); // Read header

        $mapelData = [];
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 5) continue;
            
            $mapelData[] = [
                'kode_mapel' => $row[0],
                'nama_mapel' => $row[1],
                'jenjang' => $row[2],
                'jjp' => str_replace(',', '.', $row[3]),
                'tingkat' => $row[4],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        fclose($handle);

        $this->command->info("Seeding " . count($mapelData) . " subjects...");

        DB::transaction(function () use ($mapelData) {
            foreach ($mapelData as $data) {
                Mapel::updateOrCreate(
                    [
                        'kode_mapel' => $data['kode_mapel'],
                        'nama_mapel' => $data['nama_mapel'],
                    ],
                    $data
                );
            }
        });

        $this->command->info("Mapel seeding completed successfully.");
    }
}