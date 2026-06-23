<?php

namespace App\Filament\Resources\Pengaduans\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class PengaduanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('')
                    ->columnSpanFull()
                    ->schema([
                        \Filament\Infolists\Components\ViewEntry::make('pengaduan_details')
                            ->hiddenLabel()
                            ->view('infolists.components.pengaduan-details-list'),
                    ]),
            ]);
    }
}
