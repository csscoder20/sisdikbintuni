<?php

namespace App\Filament\Resources\GtkJamAjars\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GtkJamAjarForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gtk_id')
                    ->label('GTK')
                    ->relationship('gtk', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('rombel_id')
                    ->label('Rombel')
                    ->relationship('rombel', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('mapel_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mapel', 'nama_mapel')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('jumlah_jam')
                    ->numeric()
                    ->required(),
                Select::make('semester')
                    ->options([
                        'ganjil' => 'Ganjil',
                        'genap' => 'Genap',
                    ]),
                TextInput::make('tahun_ajaran'),
            ]);
    }
}
