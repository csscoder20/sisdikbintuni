<?php

namespace App\Filament\Resources\LaporanGedung\Schemas;

use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class LaporanGedungForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Hidden::make('laporan_id')
                    ->default(function () {
                        $sekolahId = Filament::getTenant()?->id ?? session('dinas_selected_sekolah_id');
                        if (!$sekolahId) {
                            return null;
                        }
                        return \App\Models\Laporan::where('sekolah_id', $sekolahId)
                            ->orderBy('tahun', 'desc')
                            ->orderBy('bulan', 'desc')
                            ->first()?->id;
                    }),
                TextInput::make('nama_ruang')
                    ->label('Nama Ruang')
                    ->columnSpan(2)
                    ->required(),
                Select::make('status_kepemilikan')
                    ->label('Status Kepemilikan')
                    ->options([
                        'milik' => 'Milik',
                        'pinjam' => 'Pinjam',
                    ])
                    ->required()
                    ->default('milik'),
                TextInput::make('jumlah_total')
                    ->label('Jumlah Total')
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->default(0),
                TextInput::make('jumlah_baik')
                    ->label('Jumlah Baik')
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->default(0),
                TextInput::make('jumlah_rusak')
                    ->label('Jumlah Rusak')
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->rules([
                        fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get): void {
                            $total = $get('jumlah_total');
                            $baik = $get('jumlah_baik');

                            if ($total === null || $total === '' || $baik === null || $baik === '' || $value === null || $value === '') {
                                return;
                            }

                            if (((int) $baik + (int) $value) !== (int) $total) {
                                $fail('Jumlah Baik dan Jumlah Rusak harus sama dengan Jumlah Total.');
                            }
                        },
                    ]),
            ])->columns(3);
    }
}
