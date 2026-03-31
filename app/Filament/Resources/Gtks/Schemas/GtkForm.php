<?php

namespace App\Filament\Resources\Gtks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GtkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nik')
                    ->required(),
                TextInput::make('nip'),
                TextInput::make('nuptk'),
                TextInput::make('nama_gtk')
                    ->required(),
                TextInput::make('tempat_lahir'),
                DatePicker::make('tgl_lahir'),
                TextInput::make('jenis_gtk')
                    ->required(),
                TextInput::make('jenkel')
                    ->required(),
                TextInput::make('agama'),
                TextInput::make('kategori_papua'),
                TextInput::make('pendidikan_terakhir'),
                TextInput::make('status_kepegawaian'),
                TextInput::make('golongan_pegawai'),
                DatePicker::make('tmt_pegawai'),
                DatePicker::make('tgl_penempatan_sk_terakhir'),
                TextInput::make('npwp'),
                TextInput::make('no_rekening'),
            ]);
    }
}
