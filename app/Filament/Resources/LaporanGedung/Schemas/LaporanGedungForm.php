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
                    ->label('Periode Laporan')
                    ->relationship('laporan', 'id', function ($query) {
                        return $query->where('sekolah_id', Filament::getTenant()->id)
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc');
                    })
                    ->getOptionLabelFromRecordUsing(fn($record) => "Tahun {$record->tahun} - Bulan {$record->bulan}")
                    ->required(),
                TextInput::make('nama_ruang')
                    ->label('Nama Ruang')
                    ->required(),
                TextInput::make('jumlah_total')
                    ->label('Jumlah Total')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jumlah_baik')
                    ->label('Jumlah Baik')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('jumlah_rusak')
                    ->label('Jumlah Rusak')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status_kepemilikan')
                    ->label('Status Kepemilikan')
                    ->options([
                        'milik' => 'Milik',
                        'pinjam' => 'Pinjam',
                    ])
                    ->required()
                    ->default('milik'),
            ])->columns(3);
    }
}
