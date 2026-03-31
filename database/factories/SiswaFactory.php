<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => $this->faker->numerify('################'),
            'nisn' => $this->faker->numerify('##########'),
            'no_bpjs' => $this->faker->numerify('#############'),
            'nama_siswa' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tgl_lahir' => $this->faker->date(),
            'jenkel' => $this->faker->randomElement(['L', 'P']),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha']),
            'kategori_papua' => $this->faker->randomElement(['Papua', 'Non-Papua']),
            'disabilitas' => $this->faker->randomElement(['Tidak Ada', 'Tuna Rungu', 'Tuna Wicara']),
            'penerima_beasiswa' => $this->faker->randomElement(['PIP', 'BOS', 'Tidak']),
            'id_rombel' => \App\Models\Rombel::factory(),
            'nama_ayah' => $this->faker->name('male'),
            'nama_ibu' => $this->faker->name('female'),
            'nama_wali' => $this->faker->name(),
        ];
    }
}
