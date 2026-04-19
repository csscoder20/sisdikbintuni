<?php

namespace App\Filament\Resources\Mapels\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class MapelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_mapel')
                    ->label('Kode Mata Pelajaran')
                    ->maxLength(255),
                TextInput::make('nama_mapel')
                    ->label('Nama Mata Pelajaran')
                    ->required()
                    ->maxLength(255),
                TextInput::make('jjp')
                    ->label('JJP (Jumlah Jam Pelajaran)')
                    ->numeric()
                    ->integer(),
                Select::make('jenjang')
                    ->label('Jenjang')
                    ->options([
                        'sd' => 'SD',
                        'smp' => 'SMP',
                        'sma' => 'SMA',
                        'smk' => 'SMK',
                    ])
                    ->default(fn() => Filament::getTenant()?->jenjang ?: (Filament::getCurrentPanel() && in_array(Filament::getCurrentPanel()->getId(), ['sma', 'smk']) ? strtoupper(Filament::getCurrentPanel()->getId()) : null))
                    ->required(),
            ]);
    }
}
