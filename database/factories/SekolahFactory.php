<?php

namespace Database\Factories;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sekolah>
 */
class SekolahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_sekolah' => $this->faker->company() . ' School',
            'npsn' => $this->faker->numerify('########'),
            'nss' => $this->faker->numerify('###########'),
            'npwp' => $this->faker->numerify('##.###.###.#-###.###'),
            'alamat' => $this->faker->address(),
            'desa' => $this->faker->city(),
            'kecamatan' => $this->faker->city(),
            'kabupaten' => $this->faker->city(),
            'provinsi' => $this->faker->state(),
            'tahun_berdiri' => $this->faker->year(),
            'nomor_sk_pendirian' => $this->faker->bothify('SK-####/??/####'),
            'tgl_sk_pendirian' => $this->faker->date(),
            'status_sekolah' => $this->faker->randomElement(['Negeri', 'Swasta']),
            'nama_penyelenggara_yayasan' => $this->faker->company(),
            'alamat_penyelenggara_yayasan' => $this->faker->address(),
            'sk_pendirian_yayasan' => $this->faker->bothify('YAY-####/??/####'),
            'gedung_sekolah' => $this->faker->randomElement(['Milik Sendiri', 'Sewa']),
            'akreditasi_sekolah' => $this->faker->randomElement(['A', 'B', 'C']),
            'status_tanah_sekolah' => $this->faker->randomElement(['SHM', 'HGB']),
            'luas_tanah_sekolah' => $this->faker->numberBetween(500, 5000),
            'email_sekolah' => $this->faker->unique()->safeEmail(),
        ];
    }
}
