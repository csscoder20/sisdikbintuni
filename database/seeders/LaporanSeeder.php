<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Laporan;

class LaporanSeeder extends Seeder
{
    public function run()
    {
        $sekolahs = Sekolah::all();
        $adminDinas = User::role('admin_dinas')->first();
        $bulanBerjalan = min((int) now()->month, 12);

        foreach ($sekolahs as $sekolah) {
            for ($bulan = 1; $bulan <= $bulanBerjalan; $bulan++) {
                $status = match (true) {
                    $bulan <= max(1, $bulanBerjalan - 2) => 'verified',
                    $bulan === $bulanBerjalan - 1 => 'submitted',
                    default => 'draft',
                };

                Laporan::updateOrCreate(
                    [
                        'sekolah_id' => $sekolah->id,
                        'bulan' => $bulan,
                        'tahun' => now()->year,
                    ],
                    [
                        'status' => $status,
                        'tanggal_submit' => $status === 'draft' ? null : now()->subDays(($bulanBerjalan - $bulan) * 7 + 2),
                        'verified_at' => $status === 'verified' ? now()->subDays(($bulanBerjalan - $bulan) * 7 + 1) : null,
                        'verified_by' => $status === 'verified' ? $adminDinas?->id : null,
                        'is_identitas_sekolah_valid' => $status === 'verified',
                        'is_nominatif_gtk_valid' => $status === 'verified',
                        'is_nominatif_siswa_valid' => $status === 'verified',
                        'is_kondisi_sarpras_valid' => $status === 'verified',
                        'is_kondisi_gtk_valid' => $status === 'verified',
                        'is_kondisi_siswa_valid' => $status === 'verified',
                        'is_sebaran_jam_valid' => $status === 'verified',
                        'is_rekap_kehadiran_valid' => $status === 'verified',
                        'is_kelulusan_valid' => $status === 'verified',
                        'is_siswa_rombel_valid' => $status === 'verified',
                        'is_siswa_umur_valid' => $status === 'verified',
                        'is_siswa_agama_valid' => $status === 'verified',
                        'is_siswa_daerah_valid' => $status === 'verified',
                        'is_siswa_disabilitas_valid' => $status === 'verified',
                        'is_siswa_beasiswa_valid' => $status === 'verified',
                        'is_gtk_agama_valid' => $status === 'verified',
                        'is_gtk_daerah_valid' => $status === 'verified',
                        'is_gtk_status_valid' => $status === 'verified',
                        'is_gtk_umur_valid' => $status === 'verified',
                        'is_gtk_pendidikan_valid' => $status === 'verified',
                        'is_mapel_valid' => $status === 'verified',
                        'is_keuangan_valid' => $status === 'verified',
                        'is_rekening_npwp_valid' => $status === 'verified',
                    ]
                );
            }
        }
    }
}
