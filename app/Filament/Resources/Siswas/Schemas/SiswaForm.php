<?php

namespace App\Filament\Resources\Siswas\Schemas;

use App\Models\WilayahKabBintuni;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                ->label('Nama Siswa')
                    ->required(),
                TextInput::make('nisn')
                ->label('NISN')
                    ->required(),
                TextInput::make('nik')
                ->label('NIK')
                    ->required(),
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
                TextInput::make('alamat')
                ->label('Alamat Domisili')
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
                    ->afterStateUpdated(fn ($state, callable $set) => $set('desa', null))
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
            ])->columns(3);
    }
}
