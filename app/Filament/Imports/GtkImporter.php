<?php

namespace App\Filament\Imports;

use App\Models\Gtk;
use App\Models\GtkPendidikan;
use App\Models\GtkKeuangan;
use App\Models\Mengajar;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Collection;

class GtkImporter extends Importer
{
    protected static ?string $model = Gtk::class;

    public static function parseIndoDate($state): ?string
    {
        if (blank($state)) return null;
        if ($state instanceof \DateTimeInterface) {
            return $state->format('Y-m-d');
        }

        try {
            $stateString = (string) $state;
            
            // Map Indonesian months
            $months = [
                'januari' => 'January', 'februari' => 'February', 'maret' => 'March',
                'april' => 'April', 'mei' => 'May', 'juni' => 'June',
                'juli' => 'July', 'agustus' => 'August', 'september' => 'September',
                'oktober' => 'October', 'november' => 'November', 'desember' => 'December'
            ];

            $normalizedState = strtolower($stateString);
            foreach ($months as $indo => $eng) {
                if (str_contains($normalizedState, $indo)) {
                    $normalizedState = str_replace($indo, $eng, $normalizedState);
                    break;
                }
            }

            // Try standard numeric formats like dd/mm/yyyy or dd-mm-yyyy
            if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})$/', $normalizedState, $matches)) {
                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];
                if (strlen($year) === 2) $year = '20' . $year;
                return "{$year}-{$month}-{$day}";
            }

            return \Illuminate\Support\Carbon::parse($normalizedState)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; 
        }
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->label('NAMA LENGKAP')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Siti Maimunah, S.Pd'),
            ImportColumn::make('nik')
                ->label('NIK')
                ->rules(['nullable', 'max:255', 'unique:gtk,nik'])
                ->example('3201010101010005'),
            ImportColumn::make('nip')
                ->label('NIP')
                ->rules(['nullable', 'max:255', 'unique:gtk,nip'])
                ->example('198501012010012001'),
            ImportColumn::make('nokarpeg')
                ->label('NO. KARPEG')
                ->rules(['nullable', 'max:255'])
                ->example('K012345'),
            ImportColumn::make('nuptk')
                ->rules(['nullable', 'max:255'])
                ->example('1234567890123456'),
            ImportColumn::make('jenis_kelamin')
                ->label('JENIS KELAMIN')
                ->rules(['nullable', 'max:255'])
                ->example('Laki-laki')
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    $state = strtolower($state);
                    if ($state === 'l' || str_contains($state, 'laki')) return 'Laki-laki';
                    if ($state === 'p' || str_contains($state, 'perempuan')) return 'Perempuan';
                    return null;
                }),
            ImportColumn::make('tempat_lahir')
                ->label('TEMPAT LAHIR')
                ->rules(['nullable', 'max:255'])
                ->example('Sorong'),
            ImportColumn::make('tanggal_lahir')
                ->label('TANGGAL LAHIR')
                ->rules(['nullable'])
                ->example('15/05/1985')
                ->castStateUsing(fn ($state) => static::parseIndoDate($state)),
            ImportColumn::make('alamat')
                ->label('ALAMAT')
                ->rules(['nullable'])
                ->example('Jl. Pendidikan No. 45'),
            ImportColumn::make('desa')
                ->label('DESA')
                ->rules(['nullable', 'max:255'])
                ->example('Bintuni Barat'),
            ImportColumn::make('kecamatan')
                ->label('KECAMATAN')
                ->rules(['nullable', 'max:255'])
                ->example('Bintuni'),
            ImportColumn::make('kabupaten')
                ->label('KABUPATEN')
                ->rules(['nullable', 'max:255'])
                ->example('Teluk Bintuni'),
            ImportColumn::make('provinsi')
                ->label('PROVINSI')
                ->rules(['nullable', 'max:255'])
                ->example('Papua Barat'),
            ImportColumn::make('agama')
                ->label('AGAMA')
                ->rules(['nullable', 'max:255'])
                ->example('Islam'),
            ImportColumn::make('pendidikan_terakhir')
                ->label('PENDIDIKAN TERAKHIR')
                ->rules(['nullable', 'max:255'])
                ->example('S1 Pendidikan Bahasa'),
            ImportColumn::make('daerah_asal')
                ->label('STATUS OAP')
                ->rules(['nullable', 'max:255'])
                ->example('Papua')
                ->castStateUsing(fn(?string $state): string => 
                    blank($state) ? 'Non Papua' : (stripos($state, 'papua') !== false && stripos($state, 'non') === false ? 'Papua' : 'Non Papua')
                ),
            ImportColumn::make('jenis_gtk')
                ->label('JENIS GTK')
                ->rules(['nullable', 'max:255'])
                ->example('Guru')
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return 'Guru'; // Default ke Guru jika kosong
                    $state = strtolower($state);
                    if (str_contains($state, 'kepala')) return 'Kepala Sekolah';
                    if (str_contains($state, 'guru')) return 'Guru';
                    if (str_contains($state, 'admin') || str_contains($state, 'tata usaha')) return 'Tenaga Administrasi';
                    return 'Guru'; // Default jika tidak dikenal
                }),
            ImportColumn::make('status_kepegawaian')
                ->label('STATUS KEPEGAWAIAN')
                ->rules(['nullable', 'max:255'])
                ->example('PNS')
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) return null;
                    if (stripos($state, 'honorer') !== false) return 'Honorer Sekolah';
                    return $state;
                }),
            ImportColumn::make('tmt_pns')
                ->label('TMT PNS')
                ->rules(['nullable'])
                ->example('01/01/2010')
                ->castStateUsing(fn ($state) => static::parseIndoDate($state)),
            ImportColumn::make('pangkat_gol_terakhir')
                ->label('PANGKAT GOLONGAN TERAKHIR')
                ->rules(['nullable', 'max:255'])
                ->example('III/b'),
            ImportColumn::make('tmt_pangkat_gol_terakhir')
                ->label('TMT PANGKAT GOLONGAN TERAKHIR')
                ->rules(['nullable'])
                ->example('01/01/2022')
                ->castStateUsing(fn ($state) => static::parseIndoDate($state)),

            // Data Pendidikan (Will be saved in afterSave to GtkPendidikan model)
            ImportColumn::make('thn_tamat_sd')->label('TAHUN TAMAT SD')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_smp')->label('TAHUN TAMAT SMP')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_sma')->label('TAHUN TAMAT SMA')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_d1')->label('TAHUN TAMAT D1')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_d1')->label('JURUSAN D1')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_d1')->label('PERGURUAN TINGGI D1')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_d2')->label('TAHUN TAMAT D2')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_d2')->label('JURUSAN D2')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_d2')->label('PERGURUAN TINGGI D2')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_d3')->label('TAHUN TAMAT D3')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_d3')->label('JURUSAN D3')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_d3')->label('PERGURUAN TINGGI D3')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_s1')->label('TAHUN TAMAT S1')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_s1')->label('JURUSAN S1')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_s1')->label('PERGURUAN TINGGI S1')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_s2')->label('TAHUN TAMAT S2')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_s2')->label('JURUSAN S2')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_s2')->label('PERGURUAN TINGGI S2')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_tamat_s3')->label('TAHUN TAMAT S3')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_s3')->label('JURUSAN S3')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_s3')->label('PERGURUAN TINGGI S3')->fillRecordUsing(fn () => null),
            ImportColumn::make('thn_akta4')->label('TAHUN TAMAT AKTA IV')->fillRecordUsing(fn () => null),
            ImportColumn::make('jurusan_akta4')->label('JURUSAN AKTA IV')->fillRecordUsing(fn () => null),
            ImportColumn::make('perguruan_tinggi_akta4')->label('PERGURUAN TINGGI AKTA IV')->fillRecordUsing(fn () => null),
            ImportColumn::make('gelar_depan')->label('GELAR DEPAN')->fillRecordUsing(fn () => null),
            ImportColumn::make('gelar_belakang')->label('GELAR BELAKANG')->fillRecordUsing(fn () => null),

            // Data Keuangan (Will be saved in afterSave to GtkKeuangan model)
            ImportColumn::make('nomor_rekening')->label('NO. REKENING')->fillRecordUsing(fn () => null),
            ImportColumn::make('nama_bank')->label('NAMA BANK')->fillRecordUsing(fn () => null),
            ImportColumn::make('npwp')->label('NPWP')->fillRecordUsing(fn () => null),
        ];
    }

    public function resolveRecord(): ?Gtk
    {
        $sekolahId = filament()->getTenant()->id;
        
        if (!empty($this->data['nik'])) {
            $record = Gtk::where('sekolah_id', $sekolahId)
                ->where('nik', $this->data['nik'])
                ->first();
            if ($record) return $record;
        }

        if (!empty($this->data['nip'])) {
            $record = Gtk::where('sekolah_id', $sekolahId)
                ->where('nip', $this->data['nip'])
                ->first();
            if ($record) return $record;
        }

        $record = new Gtk();
        $record->sekolah_id = $sekolahId;
        
        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Berhasil mengimpor ' . number_format($import->successful_rows) . ' baris.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' Gagal mengimpor ' . number_format($failedRowsCount) . ' baris.';
        }

        return $body;
    }

    protected function afterSave(): void
    {
        $gtk = $this->record;

        // 1. Sync to Sebaran Jam Mengajar (Principals and Teachers only)
        if ($gtk->jenis_gtk !== 'Tenaga Administrasi') {
            Mengajar::firstOrCreate([
                'gtk_id' => $gtk->id,
                'rombel_id' => null,
                'mapel_id' => null,
            ]);
        }

        // 2. Sync to GtkPendidikan
        $pendidikanFields = [
            'thn_tamat_sd', 'thn_tamat_smp', 'thn_tamat_sma',
            'thn_tamat_d1', 'jurusan_d1', 'perguruan_tinggi_d1',
            'thn_tamat_d2', 'jurusan_d2', 'perguruan_tinggi_d2',
            'thn_tamat_d3', 'jurusan_d3', 'perguruan_tinggi_d3',
            'thn_tamat_s1', 'jurusan_s1', 'perguruan_tinggi_s1',
            'thn_tamat_s2', 'jurusan_s2', 'perguruan_tinggi_s2',
            'thn_tamat_s3', 'jurusan_s3', 'perguruan_tinggi_s3',
            'thn_akta4', 'jurusan_akta4', 'perguruan_tinggi_akta4',
            'gelar_depan', 'gelar_belakang'
        ];
        
        $pendidikanData = [];
        foreach ($pendidikanFields as $field) {
            if (array_key_exists($field, $this->data)) {
                $pendidikanData[$field] = $this->data[$field];
            }
        }
        
        if (!empty($pendidikanData)) {
            GtkPendidikan::updateOrCreate(
                ['gtk_id' => $gtk->id],
                $pendidikanData
            );
        }

        // 3. Sync to GtkKeuangan
        $keuanganFields = ['nomor_rekening', 'nama_bank', 'npwp'];
        $keuanganData = [];
        foreach ($keuanganFields as $field) {
            if (array_key_exists($field, $this->data)) {
                $keuanganData[$field] = $this->data[$field];
            }
        }

        if (!empty($keuanganData)) {
            GtkKeuangan::updateOrCreate(
                ['gtk_id' => $gtk->id],
                $keuanganData
            );
        }
    }
}
