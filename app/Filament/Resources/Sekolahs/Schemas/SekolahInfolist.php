<?php

namespace App\Filament\Resources\Sekolahs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SekolahInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nama_sekolah'),
                TextEntry::make('npsn')
                    ->placeholder('-'),
                TextEntry::make('nss')
                    ->placeholder('-'),
                TextEntry::make('npwp')
                    ->placeholder('-'),
                TextEntry::make('alamat')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('desa')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('kecamatan')
                    ->placeholder('-'),
                TextEntry::make('kabupaten')
                    ->placeholder('-'),
                TextEntry::make('provinsi')
                    ->placeholder('-'),
                TextEntry::make('tahun_berdiri')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('nomor_sk_pendirian')
                    ->placeholder('-'),
                TextEntry::make('tgl_sk_pendirian')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('status_sekolah')
                    ->placeholder('-'),
                TextEntry::make('nama_penyelenggara_yayasan')
                    ->placeholder('-'),
                TextEntry::make('alamat_penyelenggara_yayasan')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sk_pendirian_yayasan')
                    ->placeholder('-'),
                TextEntry::make('gedung_sekolah')
                    ->placeholder('-'),
                TextEntry::make('akreditasi_sekolah')
                    ->placeholder('-'),
                TextEntry::make('status_tanah_sekolah')
                    ->placeholder('-'),
                TextEntry::make('luas_tanah_sekolah')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('email_sekolah')
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
