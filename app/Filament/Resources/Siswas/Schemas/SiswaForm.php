<?php

namespace App\Filament\Resources\Siswas\Schemas;

use App\Models\WilayahKabBintuni;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Wizard::make([
                    Step::make('Identitas')
                        ->schema([
                            TextInput::make('nama')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('nisn')
                                ->label('NISN')
                                ->maxLength(20),
                            TextInput::make('nik')
                                ->label('NIK')
                                ->maxLength(16),
                            TextInput::make('nokk')
                                ->label('Nomor KK')
                                ->maxLength(16),
                            Radio::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])
                                ->inline()
                                ->required(),
                            TextInput::make('tempat_lahir')
                                ->label('Tempat Lahir'),
                            DatePicker::make('tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->native(false)
                                ->displayFormat('d/m/Y'),

                            TextInput::make('nobpjs')
                                ->label('Nomor BPJS')
                                ->maxLength(20),
                            Select::make('agama')
                                ->label('Agama')
                                ->options([
                                    'Islam' => 'Islam',
                                    'Kristen' => 'Kristen',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
                                ]),
                        ])->columns(2),

                    Step::make('Asal & Alamat Domisili')
                        ->schema([
                            Select::make('daerah_asal')
                                ->label('Daerah Asal')
                                ->options([
                                    'Papua' => 'Papua',
                                    'Non Papua' => 'Non Papua',
                                ]),
                            TextInput::make('alamat')
                                ->label('Alamat Domisili')
                                ->placeholder('Nama Jalan, Nomor Rumah/Blok, RT/RW, Kompleks')
                                ->columnSpanFull(),
                            TextInput::make('provinsi')
                                ->label('Provinsi')
                                ->default('Papua Barat')
                                ->disabled()
                                ->dehydrated(true),
                            TextInput::make('kabupaten')
                                ->label('Kabupaten')
                                ->default('Teluk Bintuni')
                                ->disabled()
                                ->dehydrated(true),
                            Select::make('kecamatan')
                                ->label('Kecamatan')
                                ->options(function () {
                                    return WilayahKabBintuni::whereRaw("LENGTH(REPLACE(kode, '.', '')) = 6")
                                        ->pluck('nama', 'nama');
                                })
                                ->live()
                                ->afterStateUpdated(fn($state, callable $set) => $set('desa', null))
                                ->searchable(),
                            Select::make('desa')
                                ->label('Desa/Kelurahan')
                                ->options(function (callable $get) {
                                    $kecamatan = $get('kecamatan');
                                    if (! $kecamatan) {
                                        return [];
                                    }

                                    $kecamatanModel = WilayahKabBintuni::where('nama', $kecamatan)
                                        ->whereRaw("LENGTH(REPLACE(kode, '.', '')) = 6")
                                        ->first();

                                    if (!$kecamatanModel) {
                                        return [];
                                    }

                                    return WilayahKabBintuni::where('kode', 'like', $kecamatanModel->kode . '.%')
                                        ->whereRaw("LENGTH(REPLACE(kode, '.', '')) = 10")
                                        ->pluck('nama', 'nama');
                                })
                                ->searchable(),
                        ])->columns(2),

                    Step::make('Data Orang Tua/Wali')
                        ->schema([
                            TextInput::make('nama_ayah')
                                ->label('Nama Ayah'),
                            TextInput::make('nama_ibu')
                                ->label('Nama Ibu'),
                            TextInput::make('nama_wali')
                                ->label('Nama Wali'),
                        ])->columns(2),

                    Step::make('Lainnya')
                        ->schema([
                            Select::make('rombel')
                                ->label('Rombel/Kelas')
                                ->relationship('rombel', 'nama')
                                ->searchable()
                                ->preload()
                                ->saveRelationshipsUsing(function ($record, $state, $get) {
                                    $tahunAjaran = $get('tahun_ajaran');
                                    if ($state && $tahunAjaran) {
                                        $record->rombel()->sync([
                                            $state => ['tahun_ajaran' => $tahunAjaran]
                                        ]);
                                    } else {
                                        $record->rombel()->detach();
                                    }
                                }),
                            Select::make('tahun_ajaran')
                                ->label('Tahun Ajaran')
                                ->options(function () {
                                    $currentYear = now()->year;
                                    $years = [];
                                    for ($i = -1; $i <= 1; $i++) {
                                        $year = $currentYear + $i;
                                        $label = $year . '/' . ($year + 1);
                                        $years[$label] = $label;
                                    }
                                    return $years;
                                })
                                ->default(function () {
                                    $year = now()->year;
                                    $month = now()->month;
                                    if ($month >= 7) {
                                        return $year . '/' . ($year + 1);
                                    }
                                    return ($year - 1) . '/' . $year;
                                })
                                ->required()
                                ->dehydrated(false),
                            Select::make('status')
                                ->label('Status Siswa')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'mutasi_masuk' => 'Mutasi Masuk',
                                    'mutasi_keluar' => 'Mutasi Keluar',
                                    'lulus' => 'Lulus',
                                    'putus_sekolah' => 'Putus Sekolah',
                                    'mengulang' => 'Mengulang',
                                ])
                                ->default('aktif')
                                ->required(),
                            Select::make('disabilitas')
                                ->label('Jenis Disabilitas')
                                ->options([
                                    'tidak' => 'Non Disabilitas',
                                    'tuna_netra' => 'Tuna Netra',
                                    'tuna_rungu' => 'Tuna Rungu',
                                    'tuna_wicara' => 'Tuna Wicara',
                                    'tuna_daksa' => 'Tuna Daksa',
                                    'tuna_grahita' => 'Tuna Grahita',
                                    'tuna_lainnya' => 'Lainnya',
                                ])
                                ->default('tidak')
                                ->required(),
                            Select::make('beasiswa')
                                ->label('Status Beasiswa')
                                ->options([
                                    'tidak' => 'Tidak Menerima',
                                    'beasiswa_pemerintah_pusat' => 'Beasiswa Pemerintah Pusat',
                                    'beasiswa_pemerintah_daerah' => 'Beasiswa Pemerintah Daerah',
                                    'beasisswa_swasta' => 'Beasiswa Swasta',
                                    'beasiswa_khusus' => 'Beasiswa Khusus',
                                    'beasiswa_afirmasi' => 'Beasiswa Afirmasi',
                                    'beasiswa_lainnya' => 'Beasiswa Lainnya',
                                ])
                                ->default('tidak')
                                ->required(),
                        ])->columns(2),
                ])
                    ->skippable()
                    ->submitAction(new HtmlString(Blade::render(
                        <<<'BLADE'
                        <x-filament::button
                            type="submit"
                            size="sm"
                            color="success"
                        >
                            Simpan Data
                        </x-filament::button>
                    BLADE
                    ))),
            ]);
    }
}
