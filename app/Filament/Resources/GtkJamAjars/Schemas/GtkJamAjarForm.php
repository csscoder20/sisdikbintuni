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
                
                TextInput::make('jumlah_jam')
                    ->label('Jumlah Jam Mengajar')
                    ->numeric()
                    ->required(),
                TextInput::make('jumlah_tugas_tambahan')
                    ->label('Jumlah Jam Tugas Tambahan')
                    ->numeric()
                    ->required(),
                TextInput::make('total_jam')
                    ->label('Total Jam')
                    ->numeric()
                    ->required()
                    ->disabled(),
                Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'ganjil' => 'Ganjil',
                        'genap' => 'Genap',
                    ]),
                TextInput::make('tahun_ajaran')
                    ->label('Tahun Ajaran'),
            ]);
    }
}
