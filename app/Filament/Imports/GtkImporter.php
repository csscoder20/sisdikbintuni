<?php

namespace App\Filament\Imports;

use App\Models\Gtk;
use App\Models\Mengajar;
use App\Models\GtkTugasTambahan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class GtkImporter extends Importer
{
    protected static ?string $model = Gtk::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Siti Maimunah, S.Pd'),
            ImportColumn::make('nik')
                ->rules(['string', 'max:255', 'unique:gtk,nik'])
                ->example('3201010101010005'),
            ImportColumn::make('nip')
                ->rules(['string', 'max:255', 'unique:gtk,nip'])
                ->example('198501012010012001'),
            ImportColumn::make('nokarpeg')
                ->rules(['string', 'max:255'])
                ->example('K012345'),
            ImportColumn::make('nuptk')
                ->rules(['string', 'max:255', 'unique:gtk,nuptk'])
                ->example('1234567890123456'),
            ImportColumn::make('jenis_kelamin')
                ->rules(['string', 'max:255'])
                ->example('Perempuan')
                ->castStateUsing(function (string $state): ?string {
                    $state = strtolower($state);
                    if ($state === 'l' || str_contains($state, 'laki')) return 'Laki-laki';
                    if ($state === 'p' || str_contains($state, 'perempuan')) return 'Perempuan';
                    return null;
                }),
            ImportColumn::make('tempat_lahir')
                ->rules(['string', 'max:255'])
                ->example('Sorong'),
            ImportColumn::make('tanggal_lahir')
                ->rules(['date'])
                ->example('15/05/1985')
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) return null;
                    if ($state instanceof \DateTimeInterface) {
                        return $state->format('Y-m-d');
                    }
                    try {
                        $stateString = (string) $state;
                        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/', $stateString, $matches)) {
                            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                            $year = $matches[3];
                            if (strlen($year) === 2) $year = '20' . $year;
                            return "{$year}-{$month}-{$day}";
                        }
                        return \Illuminate\Support\Carbon::parse($stateString)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return (string) $state;
                    }
                }),
            ImportColumn::make('alamat')
                ->rules(['string'])
                ->example('Jl. Pendidikan No. 45'),
            ImportColumn::make('desa')
                ->rules(['string', 'max:255'])
                ->example('Bintuni Barat'),
            ImportColumn::make('kecamatan')
                ->rules(['string', 'max:255'])
                ->example('Bintuni'),
            ImportColumn::make('kabupaten')
                ->rules(['string', 'max:255'])
                ->example('Teluk Bintuni'),
            ImportColumn::make('provinsi')
                ->rules(['string', 'max:255'])
                ->example('Papua Barat'),
            ImportColumn::make('agama')
                ->rules(['string', 'max:255'])
                ->example('Islam'),
            ImportColumn::make('pendidikan_terakhir')
                ->rules(['string', 'max:255'])
                ->example('S1 Pendidikan Bahasa'),
            ImportColumn::make('daerah_asal')
                ->rules(['string', 'max:255'])
                ->example('Papua')
                ->castStateUsing(fn(string $state): string => 
                    stripos($state, 'papua') !== false && stripos($state, 'non') === false ? 'Papua' : 'Non Papua'
                ),
            ImportColumn::make('jenis_gtk')
                ->rules(['string', 'max:255'])
                ->example('Guru'),
            ImportColumn::make('status_kepegawaian')
                ->rules(['string', 'max:255'])
                ->example('PNS'),
            ImportColumn::make('tmt_pns')
                ->rules(['date'])
                ->example('01/01/2010')
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) return null;
                    if ($state instanceof \DateTimeInterface) {
                        return $state->format('Y-m-d');
                    }
                    try {
                        $stateString = (string) $state;
                        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/', $stateString, $matches)) {
                            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                            $year = $matches[3];
                            if (strlen($year) === 2) $year = '20' . $year;
                            return "{$year}-{$month}-{$day}";
                        }
                        return \Illuminate\Support\Carbon::parse($stateString)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return (string) $state;
                    }
                }),
            ImportColumn::make('pangkat_gol_terakhir')
                ->rules(['string', 'max:255'])
                ->example('III/b'),
            ImportColumn::make('tmt_pangkat_gol_terakhir')
                ->rules(['date'])
                ->example('01/01/2022')
                ->castStateUsing(function ($state): ?string {
                    if (blank($state)) return null;
                    if ($state instanceof \DateTimeInterface) {
                        return $state->format('Y-m-d');
                    }
                    try {
                        $stateString = (string) $state;
                        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/', $stateString, $matches)) {
                            $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                            $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                            $year = $matches[3];
                            if (strlen($year) === 2) $year = '20' . $year;
                            return "{$year}-{$month}-{$day}";
                        }
                        return \Illuminate\Support\Carbon::parse($stateString)->format('Y-m-d');
                    } catch (\Exception $e) {
                        return (string) $state;
                    }
                }),

            // Data Pendidikan
            ImportColumn::make('thn_tamat_sd')->label('Thn Tamat SD')->example('1997')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_smp')->label('Thn Tamat SMP')->example('2000')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_sma')->label('Thn Tamat SMA')->example('2003')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('thn_tamat_d1')->label('Thn Tamat D1')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_d1')->label('Jurusan D1')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_d1')->label('PT D1')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('thn_tamat_d2')->label('Thn Tamat D2')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_d2')->label('Jurusan D2')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_d2')->label('PT D2')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('thn_tamat_d3')->label('Thn Tamat D3')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_d3')->label('Jurusan D3')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_d3')->label('PT D3')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('thn_tamat_s1')->label('Thn Tamat S1')->example('2007')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_s1')->label('Jurusan S1')->example('Pendidikan Matematika')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_s1')->label('PT S1')->example('UNIPA')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('thn_tamat_s2')->label('Thn Tamat S2')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_s2')->label('Jurusan S2')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_s2')->label('PT S2')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('thn_tamat_s3')->label('Thn Tamat S3')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_s3')->label('Jurusan S3')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_s3')->label('PT S3')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('thn_akta4')->label('Thn Akta IV')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_akta4')->label('Jurusan Akta IV')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_akta4')->label('PT Akta IV')->fillRecordUsing(fn () => null),
            
            ImportColumn::make('gelar_depan')->label('Gelar Depan')->fillRecordUsing(fn () => null),
            ImportColumn::make('gelar_belakang')->label('Gelar Belakang')->example('S.Pd')->fillRecordUsing(fn () => null),

            // Data Keuangan
            ImportColumn::make('nomor_rekening')->label('No Rekening')->example('1234567890')->fillRecordUsing(fn () => null),
            ImportColumn::make('nama_bank')->label('Nama Bank')->example('Bank Papua')->fillRecordUsing(fn () => null),
            ImportColumn::make('npwp')->label('NPWP')->example('12.345.678.9-123.000')->fillRecordUsing(fn () => null),
        ];
    }

    public function resolveRecord(): Gtk
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (! $sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.');
        }

        // Return new instance to trigger unique validation instead of updating
        return new Gtk(['sekolah_id' => $sekolahId]);
    }

    public function afterSave(): void
    {
        $gtk = $this->record;

        // Sync Data Pendidikan
        $pendidikanFields = [
            'thn_tamat_sd', 'thn_tamat_smp', 'thn_tamat_sma',
            'thn_tamat_d1', 'jurusan_d1', 'perguruan_tinggi_d1',
            'thn_tamat_d2', 'jurusan_d2', 'perguruan_tinggi_d2',
            'thn_tamat_d3', 'jurusan_d3', 'perguruan_tinggi_d3',
            'thn_tamat_s1', 'jurusan_s1', 'perguruan_tinggi_s1',
            'thn_tamat_s2', 'jurusan_s2', 'perguruan_tinggi_s2',
            'thn_tamat_s3', 'jurusan_s3', 'perguruan_tinggi_s3',
            'thn_akta4', 'jurusan_akta4', 'perguruan_tinggi_akta4',
            'gelar_depan', 'gelar_belakang',
        ];

        $pendidikanData = [];
        foreach ($pendidikanFields as $field) {
            if (isset($this->data[$field])) {
                $pendidikanData[$field] = $this->data[$field];
            }
        }

        if (!empty($pendidikanData)) {
            $gtk->pendidikan()->updateOrCreate(['gtk_id' => $gtk->id], $pendidikanData);
        }

        // Sync Data Keuangan
        $keuanganFields = ['nomor_rekening', 'nama_bank', 'npwp'];
        $keuanganData = [];
        foreach ($keuanganFields as $field) {
            if (isset($this->data[$field])) {
                $keuanganData[$field] = $this->data[$field];
            }
        }

        if (!empty($keuanganData)) {
            $gtk->keuangan()->updateOrCreate(['gtk_id' => $gtk->id], $keuanganData);
        }

        // Auto-create placeholder Sebaran Jam Mengajar record so the GTK name appears in the list.
        Mengajar::query()
            ->where('gtk_id', $gtk->id)
            ->whereNull('rombel_id')
            ->whereNull('mapel_id')
            ->first()
            ?? Mengajar::create([
                'gtk_id' => $gtk->id,
                'rombel_id' => null,
                'mapel_id' => null,
                'jumlah_jam' => null,
                'semester' => null,
                'tahun_ajaran' => null,
                'laporan_id' => null,
            ]);

        // Auto-create Tugas Tambahan record
        GtkTugasTambahan::query()->firstOrCreate(
            ['gtk_id' => $gtk->id],
            [
                'tugas_tambahan' => null,
                'jumlah_jam' => null,
            ],
        );
    }

    public function getValidationMessages(): array
    {
        return [
            'nik.unique' => 'NIK :input sudah terdaftar di database.',
            'nip.unique' => 'NIP :input sudah terdaftar di database.',
            'nuptk.unique' => 'NUPTK :input sudah terdaftar di database.',
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor GTK selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
