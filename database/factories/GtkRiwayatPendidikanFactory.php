<?php

namespace Database\Factories;

use App\Models\GtkRiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\Factory;

class GtkRiwayatPendidikanFactory extends Factory
{
    protected $model = GtkRiwayatPendidikan::class;

    public function definition(): array
    {
        return [
            'id_gtk' => \App\Models\Gtk::factory(),
            'thn_tamat_sd' => $this->faker->year(),
            'thn_tamat_smp' => $this->faker->year(),
            'thn_tamat_sma' => $this->faker->year(),
            'thn_tamat_s1' => $this->faker->year(),
            'jurusan_s1' => $this->faker->randomElement(['Pendidikan Matematika', 'Pendidikan Bahasa Indonesia', 'Teknik Informatika']),
            'nama_perguruan_tinggi' => $this->faker->company() . ' University',
            'gelar_belakang' => $this->faker->randomElement(['S.Pd', 'S.Kom', 'M.Pd']),
        ];
    }
}
