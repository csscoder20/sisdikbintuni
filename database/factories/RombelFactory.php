<?php

namespace Database\Factories;

use App\Models\Rombel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rombel>
 */
class RombelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_rombel' => 'Kelas ' . $this->faker->numberBetween(1, 12) . ' ' . $this->faker->randomLetter(),
            'id_sekolah' => \App\Models\Sekolah::factory(),
        ];
    }
}
