<?php

namespace App\Filament\Resources\GtkKeuangan\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GtkKeuanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gtk_id')
                    ->relationship('gtk', 'nama')
                    ->required(),
                TextInput::make('gapok')
                    ->label('Gaji Pokok')
                    ->numeric()
                    ->default(0),
                TextInput::make('tunjangan_istri')
                    ->numeric()
                    ->default(0),
                TextInput::make('tunjangan_anak')
                    ->numeric()
                    ->default(0),
                TextInput::make('tunjangan_fungsional')
                    ->numeric()
                    ->default(0),
                TextInput::make('tunjangan_daerah')
                    ->numeric()
                    ->default(0),
                TextInput::make('tunjangan_beras')
                    ->numeric()
                    ->default(0),
                TextInput::make('pajak')
                    ->numeric()
                    ->default(0),
                TextInput::make('bruto')
                    ->numeric()
                    ->default(0),
                TextInput::make('iuran_pensiun')
                    ->numeric()
                    ->default(0),
                TextInput::make('tabungan_perumahan')
                    ->numeric()
                    ->default(0),
                TextInput::make('netto')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
