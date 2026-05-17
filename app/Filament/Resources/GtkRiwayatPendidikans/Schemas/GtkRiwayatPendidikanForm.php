<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class GtkRiwayatPendidikanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('SD - SLTA')
                        ->schema([
                            Section::make('Identitas GTK')
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

                            Section::make('Pendidikan Dasar dan Menengah')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_tamat_sd')->label('Tahun Tamat SD')->numeric(),
                                    TextInput::make('thn_tamat_smp')->label('Tahun Tamat SMP')->numeric(),
                                    TextInput::make('thn_tamat_sma')->label('Tahun Tamat SMA')->numeric(),
                                ]),
                        ]),

                    Step::make('D1 - D3')
                        ->schema([
                            Section::make('Diploma 1 (D1)')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_tamat_d1')->label('Tahun Tamat D1')->numeric(),
                                    TextInput::make('jurusan_d1')->label('Jurusan D1'),
                                    TextInput::make('perguruan_tinggi_d1')->label('Perguruan Tinggi D1'),
                                ]),

                            Section::make('Diploma 2 (D2)')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_tamat_d2')->label('Tahun Tamat D2')->numeric(),
                                    TextInput::make('jurusan_d2')->label('Jurusan D2'),
                                    TextInput::make('perguruan_tinggi_d2')->label('Perguruan Tinggi D2'),
                                ]),

                            Section::make('Diploma 3 (D3)')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_tamat_d3')->label('Tahun Tamat D3')->numeric(),
                                    TextInput::make('jurusan_d3')->label('Jurusan D3'),
                                    TextInput::make('perguruan_tinggi_d3')->label('Perguruan Tinggi D3'),
                                ]),
                        ]),

                    Step::make('S1 - S3')
                        ->schema([
                            Section::make('Strata 1 (S1)')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_tamat_s1')->label('Tahun Tamat S1')->numeric(),
                                    TextInput::make('jurusan_s1')->label('Jurusan S1'),
                                    TextInput::make('perguruan_tinggi_s1')->label('Perguruan Tinggi S1'),
                                ]),

                            Section::make('Strata 2 (S2)')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_tamat_s2')->label('Tahun Tamat S2')->numeric(),
                                    TextInput::make('jurusan_s2')->label('Jurusan S2'),
                                    TextInput::make('perguruan_tinggi_s2')->label('Perguruan Tinggi S2'),
                                ]),

                            Section::make('Strata 3 (S3)')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_tamat_s3')->label('Tahun Tamat S3')->numeric(),
                                    TextInput::make('jurusan_s3')->label('Jurusan S3'),
                                    TextInput::make('perguruan_tinggi_s3')->label('Perguruan Tinggi S3'),
                                ]),
                        ]),

                    Step::make('Akta IV - Gelar')
                        ->schema([
                            Section::make('Akta IV')
                                ->columnSpanFull()
                                ->columns(3)
                                ->components([
                                    TextInput::make('thn_akta4')->label('Tahun Tamat Akta IV')->numeric(),
                                    TextInput::make('jurusan_akta4')->label('Jurusan Akta IV'),
                                    TextInput::make('perguruan_tinggi_akta4')->label('Perguruan Tinggi Akta IV'),
                                ]),

                            Section::make('Gelar')
                                ->columnSpanFull()
                                ->columns(2)
                                ->components([
                                    TextInput::make('gelar_depan')->label('Gelar Depan'),
                                    TextInput::make('gelar_belakang')->label('Gelar Belakang / Akademik'),
                                ]),
                        ]),
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
                            Simpan Data
                        </x-filament::button>
                    BLADE
                    ))),
            ]);
    }
}
