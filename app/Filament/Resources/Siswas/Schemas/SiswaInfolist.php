<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SiswaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nik'),
                TextEntry::make('nisn'),
                TextEntry::make('no_bpjs')
                    ->placeholder('-'),
                TextEntry::make('nama_siswa'),
                TextEntry::make('tempat_lahir')
                    ->placeholder('-'),
                TextEntry::make('tgl_lahir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('jenkel'),
                TextEntry::make('agama')
                    ->placeholder('-'),
                TextEntry::make('kategori_papua')
                    ->placeholder('-'),
                TextEntry::make('disabilitas')
                    ->placeholder('-'),
                TextEntry::make('penerima_beasiswa')
                    ->placeholder('-'),
                TextEntry::make('id_rombel')
                    ->numeric(),
                TextEntry::make('nama_ayah')
                    ->placeholder('-'),
                TextEntry::make('nama_ibu')
                    ->placeholder('-'),
                TextEntry::make('nama_wali')
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
