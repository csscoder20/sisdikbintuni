<?php

namespace App\Filament\Actions;

use App\Models\Laporan;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ValidateChecklistAction
{
    public static function make(
        string $name,
        string $type,
        ?\Closure $hasDataChecker = null,
        string $missingDataTitle = 'Data Belum Ada',
        string $missingDataBody = 'Belum ada data pada tabel ini. Silakan tambahkan data terlebih dahulu sebelum melakukan validasi.',
    ): Action
    {
        $statusResolver = function ($livewire) use ($type) {
            // Prefer page-provided helper when available
            if (method_exists($livewire, 'getLaporanStatus')) {
                return (bool) $livewire->getLaporanStatus($type);
            }

            // Fallback: resolve current laporan for this tenant and check column
            $sekolahId = filament()->getTenant()?->id ?? (auth()->check() ? auth()->user()->sekolah_id : null);
            $month = (int) date('m');
            $year = (int) date('Y');
            $laporan = \App\Models\Laporan::where([
                'sekolah_id' => $sekolahId,
                'bulan' => $month,
                'tahun' => $year,
            ])->first();

            $column = "is_" . Str::snake($type) . "_valid";
            return $laporan ? (bool) ($laporan->$column ?? false) : false;
        };

        return Action::make($name)
            ->label(fn($livewire) => $statusResolver($livewire) ? 'Valid' : 'Validasi')
            ->icon(fn($livewire) => $statusResolver($livewire) ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
            ->color(fn($livewire) => $statusResolver($livewire) ? 'gray' : 'warning')
            ->disabled(fn($livewire) => $statusResolver($livewire))
            ->requiresConfirmation(function ($livewire) use ($type, $hasDataChecker, $statusResolver) {
                $hasData = $hasDataChecker ? app()->call($hasDataChecker) : true;
                return $hasData && ! $statusResolver($livewire);
            })
            ->extraAttributes(fn($livewire) => [
                'style' => ($statusResolver)($livewire) ? 'cursor: not-allowed !important;' : '',
                'title' => ($statusResolver)($livewire) ? 'Data ini sudah divalidasi' : '',
                'id' => "btn-validate-{$name}",
                'wire:loading.attr' => 'data-dummy',
                'wire:loading.class' => '',
            ])
            ->modalHeading(function () use ($hasDataChecker) {
                $hasData = $hasDataChecker ? app()->call($hasDataChecker) : true;
                return $hasData ? 'Validasi Data' : '';
            })
            ->modalDescription(function () use ($hasDataChecker) {
                $hasData = $hasDataChecker ? app()->call($hasDataChecker) : true;
                return $hasData ? 'Apakah Anda yakin seluruh data sudah benar? Tindakan ini tidak dapat dibatalkan.' : '';
            })
            ->action(function (Action $action) use ($type, $hasDataChecker, $missingDataTitle, $missingDataBody) {
                if ($hasDataChecker && !app()->call($hasDataChecker)) {
                    Notification::make()
                        ->title($missingDataTitle)
                        ->body($missingDataBody)
                        ->warning()
                        ->send();

                    $action->halt();
                }

                $sekolahId = filament()->getTenant()?->id ?? Auth::user()->sekolah_id;

                // Determine current period
                $month = (int) date('m');
                $year = (int) date('Y');

                // Find or create report for current period
                $laporan = Laporan::firstOrCreate(
                    [
                        'sekolah_id' => $sekolahId,
                        'bulan' => $month,
                        'tahun' => $year,
                    ]
                );

                // Map type to column name
                $column = "is_" . Str::snake($type) . "_valid";

                $laporan->update([
                    $column => true,
                ]);

                // If validating nominatif siswa, persist snapshot into laporan_siswa tables
                if ($type === 'nominatif_siswa') {
                    $rombels = \App\Models\Rombel::where('sekolah_id', $sekolahId)->get();

                    foreach ($rombels as $rombel) {
                        $laporanSiswa = \App\Models\LaporanSiswa::firstOrCreate([
                            'laporan_id' => $laporan->id,
                            'rombel_id' => $rombel->id,
                        ]);

                        $laki = $rombel->siswa()->where('jenis_kelamin', 'LIKE', 'L%')->count();
                        $perempuan = $rombel->siswa()->where('jenis_kelamin', 'LIKE', 'P%')->count();
                        $total = $laki + $perempuan;

                        \App\Models\LaporanSiswaRekap::updateOrCreate(
                            ['laporan_siswa_id' => $laporanSiswa->id, 'kategori' => 'akhir_bulan'],
                            ['laki_laki' => $laki, 'perempuan' => $perempuan, 'total' => $total]
                        );

                        $awalExists = \App\Models\LaporanSiswaRekap::where([
                            'laporan_siswa_id' => $laporanSiswa->id,
                            'kategori' => 'awal_bulan'
                        ])->where('total', '>', 0)->exists();

                        if (! $awalExists) {
                            \App\Models\LaporanSiswaRekap::updateOrCreate(
                                ['laporan_siswa_id' => $laporanSiswa->id, 'kategori' => 'awal_bulan'],
                                ['laki_laki' => $laki, 'perempuan' => $perempuan, 'total' => $total]
                            );
                        }

                        $categoriesDisabilitas = [
                            'tidak',
                            'tuna_netra',
                            'tuna_rungu',
                            'tuna_wicara',
                            'tuna_daksa',
                            'tuna_grahita',
                            'tuna_lainnya'
                        ];

                        foreach ($categoriesDisabilitas as $cat) {
                            $c_l = $rombel->siswa()->where('disabilitas', $cat)->where('jenis_kelamin', 'LIKE', 'L%')->count();
                            $c_p = $rombel->siswa()->where('disabilitas', $cat)->where('jenis_kelamin', 'LIKE', 'P%')->count();

                            \App\Models\LaporanSiswaKategori::updateOrCreate(
                                [
                                    'laporan_siswa_id' => $laporanSiswa->id,
                                    'jenis_kategori' => 'disabilitas',
                                    'sub_kategori' => $cat
                                ],
                                [
                                    'laki_laki' => $c_l,
                                    'perempuan' => $c_p,
                                    'total' => $c_l + $c_p
                                ]
                            );
                        }

                        $categoriesBeasiswa = [
                            'tidak',
                            'beasiswa_pemerintah_pusat',
                            'beasiswa_pemerintah_daerah',
                            'beasisswa_swasta',
                            'beasiswa_khusus',
                            'beasiswa_afirmasi',
                            'beasiswa_lainnya'
                        ];

                        foreach ($categoriesBeasiswa as $cat) {
                            $c_l = $rombel->siswa()->where('beasiswa', $cat)->where('jenis_kelamin', 'LIKE', 'L%')->count();
                            $c_p = $rombel->siswa()->where('beasiswa', $cat)->where('jenis_kelamin', 'LIKE', 'P%')->count();

                            \App\Models\LaporanSiswaKategori::updateOrCreate(
                                [
                                    'laporan_siswa_id' => $laporanSiswa->id,
                                    'jenis_kategori' => 'beasiswa',
                                    'sub_kategori' => $cat
                                ],
                                [
                                    'laki_laki' => $c_l,
                                    'perempuan' => $c_p,
                                    'total' => $c_l + $c_p
                                ]
                            );
                        }

                        // --- Persist Umur per rombel (13..23) ---
                        $siswasWithDob = $rombel->siswa()->whereNotNull('tanggal_lahir')->get();
                        $ageCounts = [];
                        for ($age = 13; $age <= 23; $age++) {
                            $ageCounts[$age] = ['l' => 0, 'p' => 0, 't' => 0];
                        }
                        foreach ($siswasWithDob as $s) {
                            $umur = \Carbon\Carbon::parse($s->tanggal_lahir)->age;
                            if ($umur >= 13 && $umur <= 23) {
                                if (str_contains(strtolower($s->jenis_kelamin ?? ''), 'l')) $ageCounts[$umur]['l']++;
                                else $ageCounts[$umur]['p']++;
                                $ageCounts[$umur]['t']++;
                            }
                        }
                        foreach ($ageCounts as $age => $cnt) {
                            \App\Models\LaporanSiswaKategori::updateOrCreate([
                                'laporan_siswa_id' => $laporanSiswa->id,
                                'jenis_kategori' => 'umur',
                                'sub_kategori' => (string) $age,
                            ], [
                                'laki_laki' => $cnt['l'],
                                'perempuan' => $cnt['p'],
                                'total' => $cnt['t'],
                            ]);
                        }

                        // --- Persist Agama per rombel ---
                        $agamaMap = [
                            'islam' => 'islam',
                            'kristen' => 'kristen',
                            'katolik' => 'katolik',
                            'hindu' => 'hindu',
                            'buddha' => 'buddha',
                            'khonghucu' => 'khonghucu',
                        ];
                        $agamaCounts = array_fill_keys(array_keys($agamaMap), ['l' => 0, 'p' => 0, 't' => 0]);
                        $siswas = $rombel->siswa()->get();
                        foreach ($siswas as $s) {
                            $agama = strtolower($s->agama ?? '');
                            $field = null;
                            if (str_contains($agama, 'islam')) $field = 'islam';
                            elseif (str_contains($agama, 'kristen') || str_contains($agama, 'protestan')) $field = 'kristen';
                            elseif (str_contains($agama, 'katolik')) $field = 'katolik';
                            elseif (str_contains($agama, 'hindu')) $field = 'hindu';
                            elseif (str_contains($agama, 'budha') || str_contains($agama, 'buddha')) $field = 'buddha';
                            elseif (str_contains($agama, 'konghucu') || str_contains($agama, 'khonghucu')) $field = 'khonghucu';

                            if ($field) {
                                if (str_contains(strtolower($s->jenis_kelamin ?? ''), 'l')) $agamaCounts[$field]['l']++;
                                else $agamaCounts[$field]['p']++;
                                $agamaCounts[$field]['t']++;
                            }
                        }
                        foreach ($agamaCounts as $sub => $cnt) {
                            \App\Models\LaporanSiswaKategori::updateOrCreate([
                                'laporan_siswa_id' => $laporanSiswa->id,
                                'jenis_kategori' => 'agama',
                                'sub_kategori' => $sub,
                            ], [
                                'laki_laki' => $cnt['l'],
                                'perempuan' => $cnt['p'],
                                'total' => $cnt['t'],
                            ]);
                        }

                        // --- Persist Daerah (papua / non_papua) per rombel ---
                        $daerahCounts = ['papua' => ['l' => 0, 'p' => 0, 't' => 0], 'non_papua' => ['l' => 0, 'p' => 0, 't' => 0]];
                        foreach ($siswas as $s) {
                            $isPapua = str_contains(strtolower($s->daerah_asal ?? ''), 'papua') && !str_contains(strtolower($s->daerah_asal ?? ''), 'non');
                            $key = $isPapua ? 'papua' : 'non_papua';
                            if (str_contains(strtolower($s->jenis_kelamin ?? ''), 'l')) $daerahCounts[$key]['l']++;
                            else $daerahCounts[$key]['p']++;
                            $daerahCounts[$key]['t']++;
                        }
                        foreach ($daerahCounts as $sub => $cnt) {
                            \App\Models\LaporanSiswaKategori::updateOrCreate([
                                'laporan_siswa_id' => $laporanSiswa->id,
                                'jenis_kategori' => 'asal_daerah',
                                'sub_kategori' => $sub,
                            ], [
                                'laki_laki' => $cnt['l'],
                                'perempuan' => $cnt['p'],
                                'total' => $cnt['t'],
                            ]);
                        }
                    }
                }

                if ($type === 'nominatif_gtk') {
                    $normalizeJenisGtk = function (?string $value): string {
                        $value = strtolower(trim((string) $value));

                        return match (true) {
                            str_contains($value, 'kepala') => 'kepala_sekolah',
                            str_contains($value, 'administrasi') || str_contains($value, 'tenaga') => 'tenaga_administrasi',
                            default => 'guru',
                        };
                    };

                    $gtkGroups = \App\Models\Gtk::where('sekolah_id', $sekolahId)
                        ->get()
                        ->groupBy(fn ($gtk) => $normalizeJenisGtk($gtk->jenis_gtk));

                    $periodEndDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
                    $jenisGtkKeys = ['kepala_sekolah', 'guru', 'tenaga_administrasi'];
                    $gtkAgeRanges = [
                        'umur_20_29' => ['min' => 20, 'max' => 29],
                        'umur_30_39' => ['min' => 30, 'max' => 39],
                        'umur_40_49' => ['min' => 40, 'max' => 49],
                        'umur_50_59' => ['min' => 50, 'max' => 59],
                        'umur_60_ke_atas' => ['min' => 60, 'max' => null],
                    ];
                    $getGtkAgeRangeKey = function (int $age) use ($gtkAgeRanges): ?string {
                        foreach ($gtkAgeRanges as $key => $range) {
                            if ($age >= $range['min'] && ($range['max'] === null || $age <= $range['max'])) {
                                return $key;
                            }
                        }

                        return null;
                    };

                    foreach ($jenisGtkKeys as $jenisGtk) {
                        $gtks = $gtkGroups->get($jenisGtk, collect());

                        $laporanGtk = \App\Models\LaporanGtk::firstOrCreate([
                            'laporan_id' => $laporan->id,
                            'jenis_gtk' => $jenisGtk,
                        ]);

                        $writeCategory = function (string $jenisKategori, string $subKategori, int $jumlah) use ($laporanGtk): void {
                            \App\Models\LaporanGtkKategori::updateOrCreate(
                                [
                                    'laporan_gtk_id' => $laporanGtk->id,
                                    'jenis_kategori' => $jenisKategori,
                                    'sub_kategori' => $subKategori,
                                ],
                                ['jumlah' => $jumlah]
                            );
                        };

                        $genderSuffix = function ($gtk): string {
                            return str_starts_with(strtoupper((string) $gtk->jenis_kelamin), 'L') ? 'l' : 'p';
                        };

                        $agamaCounts = [];
                        foreach (['islam', 'kristen_protestan', 'katolik', 'hindu', 'budha', 'konghucu'] as $agama) {
                            $agamaCounts[$agama] = ['l' => 0, 'p' => 0];
                        }

                        $daerahCounts = [
                            'papua' => ['l' => 0, 'p' => 0],
                            'non_papua' => ['l' => 0, 'p' => 0],
                        ];

                        $ageCounts = [];
                        foreach (array_keys($gtkAgeRanges) as $ageKey) {
                            $ageCounts[$ageKey] = ['l' => 0, 'p' => 0];
                        }

                        $statusCounts = array_fill_keys([
                            'gol_i_a', 'gol_i_b', 'gol_i_c', 'gol_i_d',
                            'gol_ii_a', 'gol_ii_b', 'gol_ii_c', 'gol_ii_d',
                            'gol_iii_a', 'gol_iii_b', 'gol_iii_c', 'gol_iii_d',
                            'gol_iv_a', 'gol_iv_b', 'gol_iv_c', 'gol_iv_d', 'gol_iv_e',
                            'pppk', 'honorer_sekolah',
                        ], 0);

                        $pendidikanCounts = array_fill_keys(['slta', 'di', 'dii', 'diii', 's1', 's2', 's3'], 0);

                        foreach ($gtks as $gtk) {
                            $gender = $genderSuffix($gtk);

                            $agama = strtolower((string) $gtk->agama);
                            $agamaKey = null;
                            if (str_contains($agama, 'islam')) $agamaKey = 'islam';
                            elseif (str_contains($agama, 'kristen') || str_contains($agama, 'protestan')) $agamaKey = 'kristen_protestan';
                            elseif (str_contains($agama, 'katolik')) $agamaKey = 'katolik';
                            elseif (str_contains($agama, 'hindu')) $agamaKey = 'hindu';
                            elseif (str_contains($agama, 'budha') || str_contains($agama, 'buddha')) $agamaKey = 'budha';
                            elseif (str_contains($agama, 'konghucu') || str_contains($agama, 'khonghucu')) $agamaKey = 'konghucu';

                            if ($agamaKey) {
                                $agamaCounts[$agamaKey][$gender]++;
                            }

                            $daerah = strtolower((string) $gtk->daerah_asal);
                            $daerahKey = (str_contains($daerah, 'papua') && ! str_contains($daerah, 'non')) ? 'papua' : 'non_papua';
                            $daerahCounts[$daerahKey][$gender]++;

                            if ($gtk->tanggal_lahir) {
                                $umur = \Carbon\Carbon::parse($gtk->tanggal_lahir)->diffInYears($periodEndDate);
                                $ageKey = $getGtkAgeRangeKey($umur);
                                if ($ageKey) {
                                    $ageCounts[$ageKey][$gender]++;
                                }
                            }

                            $status = strtolower((string) $gtk->status_kepegawaian);
                            $golongan = strtolower(str_replace('/', '_', (string) $gtk->pangkat_gol_terakhir));
                            if (str_contains($status, 'pns') && array_key_exists('gol_' . $golongan, $statusCounts)) {
                                $statusCounts['gol_' . $golongan]++;
                            } elseif (str_contains($status, 'pppk')) {
                                $statusCounts['pppk']++;
                            } elseif (str_contains($status, 'honorer') || str_contains($status, 'gty') || str_contains($status, 'pty')) {
                                $statusCounts['honorer_sekolah']++;
                            }

                            $pendidikan = strtolower((string) $gtk->pendidikan_terakhir);
                            if (str_contains($pendidikan, 'slta') || str_contains($pendidikan, 'sma') || str_contains($pendidikan, 'smk')) $pendidikanCounts['slta']++;
                            elseif ($pendidikan === 'd1') $pendidikanCounts['di']++;
                            elseif ($pendidikan === 'd2') $pendidikanCounts['dii']++;
                            elseif ($pendidikan === 'd3' || $pendidikan === 'diii') $pendidikanCounts['diii']++;
                            elseif (str_contains($pendidikan, 's1') || str_contains($pendidikan, 'd4')) $pendidikanCounts['s1']++;
                            elseif (str_contains($pendidikan, 's2')) $pendidikanCounts['s2']++;
                            elseif (str_contains($pendidikan, 's3')) $pendidikanCounts['s3']++;
                        }

                        foreach ($agamaCounts as $agama => $count) {
                            $writeCategory('agama', "{$agama}_l", $count['l']);
                            $writeCategory('agama', "{$agama}_p", $count['p']);
                            $writeCategory('agama', "{$agama}_jml", $count['l'] + $count['p']);
                        }

                        foreach ($daerahCounts as $daerah => $count) {
                            $writeCategory('daerah', "{$daerah}_l", $count['l']);
                            $writeCategory('daerah', "{$daerah}_p", $count['p']);
                            $writeCategory('daerah', "{$daerah}_jml", $count['l'] + $count['p']);
                        }

                        foreach ($ageCounts as $ageKey => $count) {
                            $writeCategory('umur', "{$ageKey}_l", $count['l']);
                            $writeCategory('umur', "{$ageKey}_p", $count['p']);
                            $writeCategory('umur', "{$ageKey}_jml", $count['l'] + $count['p']);
                        }

                        foreach ($statusCounts as $subKategori => $jumlah) {
                            $writeCategory('status_kepegawaian', $subKategori, $jumlah);
                        }

                        foreach ($pendidikanCounts as $subKategori => $jumlah) {
                            $writeCategory('pendidikan', $subKategori, $jumlah);
                        }
                    }

                    // Sync laporan_id ke KehadiranGtk (rekap bulanan) untuk periode ini
                    $gtkIds = \App\Models\Gtk::where('sekolah_id', $sekolahId)->pluck('id');
                    \App\Models\KehadiranGtk::whereIn('gtk_id', $gtkIds)
                        ->where('bulan', $month)
                        ->where('tahun', $year)
                        ->whereNull('laporan_id')
                        ->update(['laporan_id' => $laporan->id]);

                    // Sync laporan_id ke GtkKehadiran (absensi harian) untuk periode ini
                    \App\Models\GtkKehadiran::whereIn('gtk_id', $gtkIds)
                        ->whereYear('tgl_presensi', $year)
                        ->whereMonth('tgl_presensi', $month)
                        ->whereNull('laporan_id')
                        ->update(['laporan_id' => $laporan->id]);

                    // Sync laporan_id ke Mengajar (sebaran jam mengajar) untuk GTK sekolah ini
                    \App\Models\Mengajar::whereIn('gtk_id', $gtkIds)
                        ->whereNull('laporan_id')
                        ->update(['laporan_id' => $laporan->id]);
                }

                Notification::make()
                    ->title("Data berhasil divalidasi untuk periode {$month}/{$year}")
                    ->success()
                    ->send();
                // Emit event to let KeadaanSiswa refresh when validation done
                try {
                    $livewire = $action->getLivewire();
                    if ($livewire) {
                        $livewire->emit('laporanUpdated', $laporan->id);
                    }
                } catch (\Throwable $e) {
                    // non-fatal: ignore if emit not available
                }
            });
    }
}
