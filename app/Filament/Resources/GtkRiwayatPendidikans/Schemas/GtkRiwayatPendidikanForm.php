<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GtkRiwayatPendidikanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id_gtk')
                    ->required()
                    ->numeric(),
                TextInput::make('thn_tamat_sd')
                    ->numeric(),
                TextInput::make('thn_tamat_smp')
                    ->numeric(),
                TextInput::make('thn_tamat_sma')
                    ->numeric(),
                TextInput::make('thn_tamat_d1')
                    ->numeric(),
                TextInput::make('jurusan_d1'),
                TextInput::make('thn_tamat_d2')
                    ->numeric(),
                TextInput::make('jurusan_d2'),
                TextInput::make('thn_tamat_d3')
                    ->numeric(),
                TextInput::make('jurusan_d3'),
                TextInput::make('thn_tamat_s1')
                    ->numeric(),
                TextInput::make('jurusan_s1'),
                TextInput::make('thn_tamat_s2')
                    ->numeric(),
                TextInput::make('jurusan_s2'),
                TextInput::make('thn_tamat_s3')
                    ->numeric(),
                TextInput::make('jurusan_s3'),
                TextInput::make('thn_akta_1')
                    ->numeric(),
                TextInput::make('jurusan_akta_1'),
                TextInput::make('thn_akta_2')
                    ->numeric(),
                TextInput::make('jurusan_akta_2'),
                TextInput::make('thn_akta_3')
                    ->numeric(),
                TextInput::make('jurusan_akta_3'),
                TextInput::make('thn_akta_4')
                    ->numeric(),
                TextInput::make('jurusan_akta_4'),
                TextInput::make('nama_perguruan_tinggi'),
                TextInput::make('gelar_akademik'),
            ]);
    }
}
