<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
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
                Select::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                TextInput::make('tempat_lahir')
                ->label('Tempat Lahir'),
                DatePicker::make('tanggal_lahir')
                ->label('Tanggal Lahir'),
                TextInput::make('agama')
                ->label('Agama'),
                TextInput::make('alamat')
                ->label('Alamat Lengkap')
                    ->columnSpanFull(),
                TextInput::make('provinsi')
                ->label('Provinsi'),
                TextInput::make('kabupaten')
                ->label('Kabupaten'),
                TextInput::make('kecamatan')
                ->label('Kecamatan'),
                TextInput::make('desa')
                ->label('Desa'),
                TextInput::make('tahun_masuk')
                ->label('Tahun Masuk')
                    ->numeric(),
                TextInput::make('tahun_keluar')
                ->label('Tahun Keluar')
                    ->numeric(),
                Select::make('status_siswa')
                ->label('Status Siswa')
                    ->options([
                        'Aktif' => 'Aktif',
                        'Lulus' => 'Lulus',
                        'Pindah' => 'Pindah',
                        'Drop Out' => 'Drop Out',
                    ]),
            ])->columns(3);
    }
}
