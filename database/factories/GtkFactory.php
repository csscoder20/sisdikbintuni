<?php

namespace Database\Factories;

use App\Models\Gtk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gtk>
 */
class GtkFactory extends Factory
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
            'nip' => $this->faker->numerify('##################'),
            'nuptk' => $this->faker->numerify('################'),
            'nama_gtk' => $this->faker->name(),
            'tempat_lahir' => $this->faker->city(),
            'tgl_lahir' => $this->faker->date(),
            'jenis_gtk' => $this->faker->randomElement(['Kepsek', 'Guru', 'Tenaga Kependidikan']),
            'jenkel' => $this->faker->randomElement(['L', 'P']),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha']),
            'kategori_papua' => $this->faker->randomElement(['Papua', 'Non-Papua']),
            'pendidikan_terakhir' => $this->faker->randomElement(['S1', 'S2', 'D3']),
            'status_kepegawaian' => $this->faker->randomElement(['PNS', 'PPPK', 'Honorer']),
            'golongan_pegawai' => $this->faker->randomElement(['III/a', 'III/b', 'IV/a']),
            'tmt_pegawai' => $this->faker->date(),
            'tgl_penempatan_sk_terakhir' => $this->faker->date(),
            'npwp' => $this->faker->numerify('##.###.###.#-###.###'),
            'no_rekening' => $this->faker->bankAccountNumber(),
            'id_sekolah' => \App\Models\Sekolah::factory(),
        ];
    }
}
