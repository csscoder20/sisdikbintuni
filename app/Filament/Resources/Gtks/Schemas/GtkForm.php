<?php

namespace App\Filament\Resources\Gtks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class GtkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                ->label('Nama GTK')
                    ->required(),
                TextInput::make('nik')
                ->label('NIK'),
                TextInput::make('nip')
                ->label('NIP'),
                TextInput::make('nokarpeg')
                ->label('No Karpeg'),
                TextInput::make('nuptk')
                ->label('NUPTK'),
                Select::make('jenis_kelamin')
                ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                TextInput::make('tempat_lahir')
                ->label('Tempat Lahir'),
                DatePicker::make('tanggal_lahir')
                ->label('Tanggal Lahir'),
                TextInput::make('agama')
                ->label('Agama'),
                TextInput::make('pendidikan_terakhir')
                ->label('Pendidikan Terakhir'),
                Select::make('daerah_asal')
                ->label('Daerah Asal')
                    ->options([
                        'Papua' => 'Papua',
                        'Non Papua' => 'Non Papua',
                    ]),
                Select::make('jenis_gtk')
                ->label('Jenis GTK')
                    ->options([
                        'Kepala Sekolah' => 'Kepala Sekolah',
                        'Guru' => 'Guru',
                        'Tenaga Administrasi' => 'Tenaga Administrasi',
                    ]),
                Select::make('status_kepegawaian')
                ->label('Status Kepegawaian')
                    ->options([
                        'PNS' => 'PNS',
                        'CPNS' => 'CPNS',
                        'PPPK' => 'PPPK',
                        'GTY/PTY' => 'GTY/PTY',
                        'Kontrak' => 'Kontrak',
                        'Honorer Sekolah' => 'Honorer Sekolah',
                    ]),
                DatePicker::make('tmt_pns')
                ->label('TMT PNS'),
                TextInput::make('pangkat_gol_terakhir')
                ->label('Pangkat Gol Terakhir'),
                DatePicker::make('tmt_pangkat_gol_terakhir')
                ->label('TMT Pangkat Gol Terakhir'),
                TextInput::make('alamat')
                ->label('Alamat Lengkap')
                    ->columnSpanFull(),
                TextInput::make('desa')
                ->label('Desa'),
                TextInput::make('kecamatan')
                ->label('Kecamatan'),
                TextInput::make('kabupaten')
                ->label('Kabupaten'),
                TextInput::make('provinsi')
                ->label('Provinsi'),
            ]);
    }
}
