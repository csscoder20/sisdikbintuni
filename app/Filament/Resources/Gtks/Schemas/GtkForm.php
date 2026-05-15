<?php

namespace App\Filament\Resources\Gtks\Schemas;

use App\Models\WilayahKabBintuni;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class GtkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Identitas Diri')
                        ->schema([
                            TextInput::make('nama')
                                ->label('Nama GTK (Lengkap)')
                                ->required(),
                            TextInput::make('nik')
                                ->label('NIK')
                                ->maxLength(16),
                            Radio::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])
                                ->inline(),
                            TextInput::make('tempat_lahir')
                                ->label('Tempat Lahir'),
                            DatePicker::make('tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->native(false)
                                ->displayFormat('d/m/Y'),
                            Select::make('agama')
                                ->label('Agama')
                                ->options([
                                    'Islam' => 'Islam',
                                    'Kristen Protestan' => 'Kristen Protestan',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
                                ]),
                            TextInput::make('pendidikan_terakhir')
                                ->label('Pendidikan Terakhir'),
                            Select::make('daerah_asal')
                                ->label('Daerah Asal')
                                ->options([
                                    'Papua' => 'Papua',
                                    'Non Papua' => 'Non Papua',
                                ]),
                        ])->columns(3),

                    Step::make('Data Kepegawaian')
                        ->schema([
                            TextInput::make('nip')
                                ->label('NIP')
                                ->maxLength(18),
                            TextInput::make('nokarpeg')
                                ->label('No Karpeg'),
                            TextInput::make('nuptk')
                                ->label('NUPTK')
                                ->maxLength(16),
                            Select::make('jenis_gtk')
                                ->label('Jenis GTK')
                                ->options([
                                    'Kepala Sekolah' => 'Kepala Sekolah',
                                    'Guru' => 'Guru',
                                    'Tenaga Administrasi' => 'Tenaga Administrasi',
                                ]),
                            Select::make('status_kepegawaian')
                                ->label('Status Kepegawaian')
                                ->options([
                                    'PNS' => 'PNS',
                                    'CPNS' => 'CPNS',
                                    'PPPK' => 'PPPK',
                                    'GTY/PTY' => 'GTY/PTY',
                                    'Kontrak' => 'Kontrak',
                                    'Honorer Sekolah' => 'Honorer Sekolah',
                                ]),
                            DatePicker::make('tmt_pns')
                                ->native(false)
                                ->label('TMT PNS')
                                ->displayFormat('d/m/Y'),
                            Select::make('pangkat_gol_terakhir')
                                ->label('Pangkat Gol Terakhir')
                                ->searchable()
                                ->options([
                                    'I/a' => 'PNS Gol I/a',
                                    'I/b' => 'PNS Gol I/b',
                                    'I/c' => 'PNS Gol I/c',
                                    'I/d' => 'PNS Gol I/d',
                                    'II/a' => 'PNS Gol II/a',
                                    'II/b' => 'PNS Gol II/b',
                                    'II/c' => 'PNS Gol II/c',
                                    'II/d' => 'PNS Gol II/d',
                                    'III/a' => 'PNS Gol III/a',
                                    'III/b' => 'PNS Gol III/b',
                                    'III/c' => 'PNS Gol III/c',
                                    'III/d' => 'PNS Gol III/d',
                                    'IV/a' => 'PNS Gol IV/a',
                                    'IV/b' => 'PNS Gol IV/b',
                                    'IV/c' => 'PNS Gol IV/c',
                                    'IV/d' => 'PNS Gol IV/d',
                                    'IV/e' => 'PNS Gol IV/e',
                                    'PPPK' => 'PPPK',
                                    'HONORER' => 'HONORER',
                                ]),
                            DatePicker::make('tmt_pangkat_gol_terakhir')
                                ->native(false)
                                ->label('TMT Pangkat Gol Terakhir')
                                ->displayFormat('d/m/Y'),
                        ])->columns(3),

                    Step::make('Alamat Domisili')
                        ->schema([
                            TextInput::make('alamat')
                                ->label('Alamat Domisili')
                                ->placeholder('Nama Jalan, Nomor Rumah/Blok, RT/RW, Kompleks')
                                ->columnSpanFull(),
                            TextInput::make('provinsi')
                                ->label('Provinsi')
                                ->default('Papua Barat')
                                ->formatStateUsing(fn($state) => $state ?: 'Papua Barat')
                                ->disabled()
                                ->dehydrated(true),
                            TextInput::make('kabupaten')
                                ->label('Kabupaten')
                                ->default('Teluk Bintuni')
                                ->formatStateUsing(fn($state) => $state ?: 'Teluk Bintuni')
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
                                ->label('Desa')
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

                    Step::make('Data Rekening')
                        ->schema([
                            Select::make('nama_bank_gaji')
                                        ->label('Nama Bank (Gaji)')
                                        ->options([
                                            'Bank Papua' => 'Bank Papua',
                                            'BRI' => 'BRI',
                                            'BNI' => 'BNI',
                                            'Mandiri' => 'Mandiri',
                                            'BCA' => 'BCA',
                                            'BSI' => 'BSI',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->searchable(),
                                    TextInput::make('no_rek_gaji')
                                        ->label('Nomor Rekening (Gaji)')
                                        ->extraInputAttributes(['inputmode' => 'numeric']),

                            Select::make('nama_bank_tunjangan')
                                        ->label('Nama Bank (Tunjangan)')
                                        ->options([
                                            'Bank Papua' => 'Bank Papua',
                                            'BRI' => 'BRI',
                                            'BNI' => 'BNI',
                                            'Mandiri' => 'Mandiri',
                                            'BCA' => 'BCA',
                                            'BSI' => 'BSI',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->searchable(),
                                    TextInput::make('no_rek_tunjangan')
                                        ->label('Nomor Rekening (Tunjangan)')
                                        ->extraInputAttributes(['inputmode' => 'numeric']),
                                    TextInput::make('npwp')
                                        ->label('NPWP'),
                        ])->columns(3),
                ])
                    ->skippable()
                    ->columnSpanFull()
                    ->submitAction(new HtmlString(Blade::render(
                        <<<'BLADE'
                        <x-filament::button
                            type="submit"
                            size="sm"
                            color="success"
                        >
                            Simpan Data GTK
                        </x-filament::button>
                    BLADE
                    ))),
            ]);
    }
}
