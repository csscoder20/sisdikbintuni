<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

use Filament\Schemas\Schema;

class GtkRiwayatPendidikanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('IDENTITAS GTK')
                    ->columnSpanFull()
                    ->components([
                        Select::make('gtk_id')
                            ->label('GTK')
                            ->relationship('gtk', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Group 1: PENDIDIKAN DASAR & MENENGAH')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_tamat_sd')->label('Thn Tamat SD')->numeric(),
                        TextInput::make('thn_tamat_smp')->label('Thn Tamat SMP')->numeric(),
                        TextInput::make('thn_tamat_sma')->label('Thn Tamat SMA')->numeric(),
                    ]),

                Section::make('Group 2: DIPLOMA 1 (D1)')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_tamat_d1')->label('Thn Tamat D1')->numeric(),
                        TextInput::make('jurusan_d1')->label('Jurusan D1'),
                        TextInput::make('perguruan_tinggi_d1')->label('PT D1'),
                    ]),

                Section::make('Group 3: DIPLOMA 2 (D2)')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_tamat_d2')->label('Thn Tamat D2')->numeric(),
                        TextInput::make('jurusan_d2')->label('Jurusan D2'),
                        TextInput::make('perguruan_tinggi_d2')->label('PT D2'),
                    ]),

                Section::make('Group 4: DIPLOMA 3 (D3)')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_tamat_d3')->label('Thn Tamat D3')->numeric(),
                        TextInput::make('jurusan_d3')->label('Jurusan D3'),
                        TextInput::make('perguruan_tinggi_d3')->label('PT D3'),
                    ]),

                Section::make('Group 5: STRATA 1 (S1)')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_tamat_s1')->label('Thn Tamat S1')->numeric(),
                        TextInput::make('jurusan_s1')->label('Jurusan S1'),
                        TextInput::make('perguruan_tinggi_s1')->label('PT S1'),
                    ]),

                Section::make('Group 6: STRATA 2 (S2)')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_tamat_s2')->label('Thn Tamat S2')->numeric(),
                        TextInput::make('jurusan_s2')->label('Jurusan S2'),
                        TextInput::make('perguruan_tinggi_s2')->label('PT S2'),
                    ]),

                Section::make('Group 7: STRATA 3 (S3)')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_tamat_s3')->label('Thn Tamat S3')->numeric(),
                        TextInput::make('jurusan_s3')->label('Jurusan S3'),
                        TextInput::make('perguruan_tinggi_s3')->label('PT S3'),
                    ]),

                Section::make('Group 8: AKTA IV')
                    ->columnSpanFull()
                    ->columns(3)
                    ->components([
                        TextInput::make('thn_akta4')->label('Thn Tamat Akta IV')->numeric(),
                        TextInput::make('jurusan_akta4')->label('Jurusan Akta IV'),
                        TextInput::make('perguruan_tinggi_akta4')->label('PT Akta IV'),
                    ]),

                Section::make('Group 9: GELAR')
                    ->columnSpanFull()
                    ->components([
                        TextInput::make('gelar_akademik')->label('Gelar Akademik'),
                    ]),
            ]);
    }
}
