<?php

namespace App\Filament\Resources\Rombels\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RombelInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama'),
                TextEntry::make('sekolah.nama'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
