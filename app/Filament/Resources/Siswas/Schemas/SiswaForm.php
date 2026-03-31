<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nik')
                    ->required(),
                TextInput::make('nisn')
                    ->required(),
                TextInput::make('no_bpjs'),
                TextInput::make('nama_siswa')
                    ->required(),
                TextInput::make('tempat_lahir'),
                DatePicker::make('tgl_lahir'),
                TextInput::make('jenkel')
                    ->required(),
                TextInput::make('agama'),
                TextInput::make('kategori_papua'),
                TextInput::make('disabilitas'),
                TextInput::make('penerima_beasiswa'),
                TextInput::make('id_rombel')
                    ->required()
                    ->numeric(),
                TextInput::make('nama_ayah'),
                TextInput::make('nama_ibu'),
                TextInput::make('nama_wali'),
            ]);
    }
}
