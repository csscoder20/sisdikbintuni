<?php

namespace App\Filament\Resources\Sekolahs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_sekolah')
                ->label('Nama Sekolah')
                    ->required(),
                TextInput::make('npsn')
                ->label('NPSN'),
                TextInput::make('nss')
                ->label('NSS'),
                TextInput::make('npwp')
                ->label('NPWP'),
                Textarea::make('alamat')
                ->label('Alamat')
                    ->columnSpanFull(),
                TextInput::make('desa')
                ->label('Desa')
                    ->numeric(),
                TextInput::make('kecamatan')
                ->label('Kecamatan'),
                TextInput::make('kabupaten')
                ->label('Kabupaten'),
                TextInput::make('provinsi')
                ->label('Provinsi'),
                TextInput::make('tahun_berdiri')
                ->label('Tahun Berdiri')
                    ->numeric(),
                TextInput::make('nomor_sk_pendirian')
                ->label('Nomor SK Pendirian'),
                DatePicker::make('tgl_sk_pendirian')
                ->label('Tanggal SK Pendirian'),
                TextInput::make('status_sekolah')
                ->label('Status Sekolah'),
                TextInput::make('nama_penyelenggara_yayasan')
                ->label('Nama Penyelenggara Yayasan'),
                Textarea::make('alamat_penyelenggara_yayasan')
                ->label('Alamat Penyelenggara Yayasan')
                    ->columnSpanFull(),
                TextInput::make('sk_pendirian_yayasan')
                ->label('SK Pendirian Yayasan'),
                TextInput::make('gedung_sekolah')
                ->label('Gedung Sekolah'),
                TextInput::make('akreditasi_sekolah')
                ->label('Akreditasi Sekolah'),
                TextInput::make('status_tanah_sekolah')
                ->label('Status Tanah Sekolah'),
                TextInput::make('luas_tanah_sekolah')
                ->label('Luas Tanah Sekolah')
                    ->numeric(),
                TextInput::make('email_sekolah')
                ->label('Email Sekolah')
                    ->email(),
            ])->columns(3);
    }
}
