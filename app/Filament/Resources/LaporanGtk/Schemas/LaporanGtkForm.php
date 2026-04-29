<?php

namespace App\Filament\Resources\LaporanGtk\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class LaporanGtkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('laporan_id')
                    ->relationship('laporan', 'id')
                    ->required(),
                Select::make('jenis_gtk')
                    ->label('Jenis GTK')
                    ->options([
                        'kepala_sekolah' => 'Kepala Sekolah',
                        'guru' => 'Guru',
                        'tenaga_administrasi' => 'Tenaga Administrasi',
                    ])
                    ->required(),
            ]);
    }
}
