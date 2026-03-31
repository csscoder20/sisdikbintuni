<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GtkRiwayatPendidikanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id_gtk')
                    ->numeric(),
                TextEntry::make('thn_tamat_sd')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_smp')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_sma')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_d1')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_d1')
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_d2')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_d2')
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_d3')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_d3')
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_s1')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_s1')
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_s2')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_s2')
                    ->placeholder('-'),
                TextEntry::make('thn_tamat_s3')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_s3')
                    ->placeholder('-'),
                TextEntry::make('thn_akta_1')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_akta_1')
                    ->placeholder('-'),
                TextEntry::make('thn_akta_2')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_akta_2')
                    ->placeholder('-'),
                TextEntry::make('thn_akta_3')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_akta_3')
                    ->placeholder('-'),
                TextEntry::make('thn_akta_4')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jurusan_akta_4')
                    ->placeholder('-'),
                TextEntry::make('nama_perguruan_tinggi')
                    ->placeholder('-'),
                TextEntry::make('gelar_akademik')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
