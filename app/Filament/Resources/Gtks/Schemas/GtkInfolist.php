<?php

namespace App\Filament\Resources\Gtks\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GtkInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nik'),
                TextEntry::make('nip')
                    ->placeholder('-'),
                TextEntry::make('nuptk')
                    ->placeholder('-'),
                TextEntry::make('nama'),
                TextEntry::make('tempat_lahir')
                    ->placeholder('-'),
                TextEntry::make('tanggal_lahir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('jenis_gtk'),
                TextEntry::make('jenis_kelamin'),
                TextEntry::make('agama')
                    ->placeholder('-'),
                TextEntry::make('kategori_papua')
                    ->placeholder('-'),
                TextEntry::make('pendidikan_terakhir')
                    ->placeholder('-'),
                TextEntry::make('status_kepegawaian')
                    ->placeholder('-'),
                TextEntry::make('golongan_pegawai')
                    ->placeholder('-'),
                TextEntry::make('tmt_pegawai')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('tgl_penempatan_sk_terakhir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('npwp')
                    ->placeholder('-'),
                TextEntry::make('no_rekening')
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
