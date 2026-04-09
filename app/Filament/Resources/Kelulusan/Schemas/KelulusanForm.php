<?php

namespace App\Filament\Resources\Kelulusan\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KelulusanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tahun')
                    ->numeric()
                    ->required(),
                TextInput::make('jumlah_lulus')
                    ->numeric()
                    ->required(),
                TextInput::make('persentase_kelulusan')
                    ->numeric()
                    ->suffix('%'),
                TextInput::make('jumlah_lanjut_pt')
                    ->label('Jumlah Lanjut ke Perguruan Tinggi')
                    ->numeric(),
            ]);
    }
}
