<?php

namespace Database\Seeders;

use App\Models\OperatorSekolah;
use App\Models\Sekolah;
use App\Models\User;
use Database\Seeders\Concerns\RealisticDummyData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OperatorSekolahSeeder extends Seeder
{
    use RealisticDummyData;

    public function run(): void
    {
        foreach (Sekolah::all() as $index => $sekolah) {
            $seed = 900000 + $index;
            $gender = $index % 2 === 0 ? 'L' : 'P';
            $name = $this->personName($gender, $seed, true);
            $emailSlug = Str::slug(strtolower($sekolah->npsn));

            $user = User::updateOrCreate(
                ['email' => $emailSlug . '@dikporabintuni.com'],
                [
                    'name' => $name,
                    'nohp' => $this->phone($seed),
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );

            $user->syncRoles(['operator']);

            OperatorSekolah::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'sekolah_id' => $sekolah->id,
                    'status' => 'approved',
                ]
            );
        }
    }
}
