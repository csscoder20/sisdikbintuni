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
                    ->default(function() {
                        $jenjang = Filament::getTenant()?->jenjang ?: (Filament::getCurrentPanel() && in_array(Filament::getCurrentPanel()->getId(), ['sma', 'smk']) ? Filament::getCurrentPanel()->getId() : null);
                        return $jenjang ? strtolower($jenjang) : null;
                    })
                    ->disabled(function() {
                        $jenjang = Filament::getTenant()?->jenjang ?: (Filament::getCurrentPanel() && in_array(Filament::getCurrentPanel()->getId(), ['sma', 'smk']) ? Filament::getCurrentPanel()->getId() : null);
                        return !empty($jenjang);
                    })
                    ->dehydrated()
                    ->required(),
            ]);
    }
}
