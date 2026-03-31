<?php

namespace App\Filament\Resources\GedungRuangs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GedungRuangInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_gedung_ruang'),
                TextEntry::make('jumlah')
                    ->numeric(),
                TextEntry::make('kondisi_baik')
                    ->numeric(),
                TextEntry::make('kondisi_rusak')
                    ->numeric(),
                TextEntry::make('status_kepemilikan')
                    ->placeholder('-'),
                TextEntry::make('id_sekolah')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
