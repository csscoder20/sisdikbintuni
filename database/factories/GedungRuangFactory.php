<?php

namespace Database\Factories;

use App\Models\GedungRuang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GedungRuang>
 */
class GedungRuangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jumlah = $this->faker->numberBetween(10, 50);
        $baik = $this->faker->numberBetween(5, $jumlah);
        $rusak = $jumlah - $baik;

        return [
            'nama_gedung_ruang' => $this->faker->randomElement(['Ruang Kelas', 'Laboratorium', 'Perpustakaan', 'Kantor Guru']),
            'jumlah' => $jumlah,
            'kondisi_baik' => $baik,
            'kondisi_rusak' => $rusak,
            'status_kepemilikan' => $this->faker->randomElement(['milik', 'pinjam']),
            'id_sekolah' => \App\Models\Sekolah::factory(),
        ];
    }
}
