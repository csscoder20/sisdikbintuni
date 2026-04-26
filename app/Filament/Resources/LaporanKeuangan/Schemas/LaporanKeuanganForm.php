<?php

namespace App\Filament\Resources\LaporanKeuangan\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class LaporanKeuanganForm
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
                    ->required()
                    ->columnSpanFull(),
                Select::make('sumber_dana')
                    ->label('Sumber Dana')
                    ->options([
                        'BOS Pusat' => 'BOS Pusat',
                        'BOS Daerah' => 'BOS Daerah',
                        'BOP' => 'BOP',
                        'Komite' => 'Komite Sekolah',
                        'Dana Desa' => 'Dana Desa',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                TextInput::make('penerimaan')
                    ->label('Total Penerimaan (Rp)')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('pengeluaran')
                    ->label('Total Pengeluaran (Rp)')
                    ->numeric()
                    ->default(0)
                    ->required(),
                TextInput::make('saldo')
                    ->label('Saldo Akhir (Rp)')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->columnSpanFull(),
            ])->columns(2);
    }
}
