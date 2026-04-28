<?php

namespace App\Filament\Actions;

use App\Models\Laporan;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ValidateChecklistAction
{
    public static function make(string $name, string $type, ?\Closure $hasDataChecker = null): Action
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
            ->action(function (Action $action) use ($type, $hasDataChecker) {
                if ($hasDataChecker && !app()->call($hasDataChecker)) {
                    Notification::make()
                        ->title('Data Belum Ada')
                        ->body('Belum ada data pada tabel ini. Silakan tambahkan data terlebih dahulu sebelum melakukan validasi.')
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
