<?php

namespace App\Filament\Resources\LaporanGedung\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class LaporanGedungForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('laporan_id')
                    ->label('Laporan Period')
                    ->relationship('laporan', 'id', function ($query) {
                        return $query->where('sekolah_id', Filament::getTenant()->id)
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Tahun {$record->tahun} - Bulan {$record->bulan}")
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('nama_ruang')
                    ->required(),
                TextInput::make('jumlah_total')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jumlah_baik')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jumlah_rusak')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status_kepemilikan')
                    ->options([
                        'milik' => 'Milik',
                        'pinjam' => 'Pinjam',
                    ])
                    ->required()
                    ->default('milik'),
            ]);
    }
}
