<?php

namespace Database\Factories;

use App\Models\PeriodeLaporan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodeLaporanFactory extends Factory
{
    protected $model = PeriodeLaporan::class;

    public function definition(): array
    {
        return [
            'tahun' => $this->faker->year(),
            'bulan' => $this->faker->numberBetween(1, 12),
        ];
    }
}
