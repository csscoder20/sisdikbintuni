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
                TextInput::make('nomor_rekening')
                    ->label('Nomor Rekening'),
                Select::make('nama_bank')
                    ->label('Nama Bank')
                    ->options([
                        'BRI' => 'Bank Rakyat Indonesia',
                        'BNI' => 'Bank Negara Indonesia',
                        'MANDIRI' => 'Bank Mandiri',
                        'BCA' => 'Bank Central Asia',
                        'BTPN' => 'Bank Tabungan Pensiunan Nasional',
                        'BTPN SYARIAH' => 'BTPN Syariah',
                        'BANK PAPUA' => 'Bank Papua',
                        'PERMATA' => 'Bank Permata',
                        'LAINNYA' => 'Lainnya',
                    ])
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('npwp')
                    ->label('NPWP'),
            ])->columns(3);
    }
}
