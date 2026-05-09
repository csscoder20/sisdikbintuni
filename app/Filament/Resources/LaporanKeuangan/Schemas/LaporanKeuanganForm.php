<?php

namespace App\Filament\Resources\LaporanKeuangan\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Facades\Filament;
use Filament\Support\RawJs;

class LaporanKeuanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Select::make('laporan_id')
                            ->label('Periode Laporan')
                            ->relationship('laporan', 'id', function ($query) {
                                return $query->where('sekolah_id', Filament::getTenant()->id)
                                    ->orderBy('tahun', 'desc')
                                    ->orderBy('bulan', 'desc');
                            })
                            ->exists('laporan', 'id', fn($rule) => $rule->where('sekolah_id', Filament::getTenant()?->id))
                            ->getOptionLabelFromRecordUsing(fn($record) => "Tahun {$record->tahun} - Bulan {$record->bulan}")
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn($state, $set) => self::updateTanggalDefault($state, $set)),
                        DatePicker::make('tanggal')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->default(now()),
                        Select::make('sumber_dana')
                            ->label('Sumber Dana')
                            ->options([
                                'BOS Pusat' => 'BOS Pusat',
                                'BOS Daerah' => 'BOS Daerah',
                                'BOP' => 'BOP',
                                'Komite' => 'Komite Sekolah',
                                'Dana Desa' => 'Dana Desa',
                                'Lainnya' => 'Lainnya',
                            ])
                            ->required(),
                    ]),
                Grid::make(3)
                    ->schema([
                        TextInput::make('penerimaan')
                            ->label('Debet (Masuk)')
                            ->placeholder('Rp 0')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->stripCharacters('.')
                            ->numeric()
                            ->default(0)
                            ->formatStateUsing(fn($state) => self::formatNominal($state))
                            ->dehydrateStateUsing(fn($state) => self::parseNominal($state))
                            ->live()
                            ->afterStateUpdated(fn(callable $set, callable $get) => self::updateSaldo($set, $get))
                            ->required(),
                        TextInput::make('pengeluaran')
                            ->label('Kredit (Keluar)')
                            ->placeholder('Rp 0')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->stripCharacters('.')
                            ->numeric()
                            ->default(0)
                            ->formatStateUsing(fn($state) => self::formatNominal($state))
                            ->dehydrateStateUsing(fn($state) => self::parseNominal($state))
                            ->live()
                            ->afterStateUpdated(fn(callable $set, callable $get) => self::updateSaldo($set, $get))
                            ->required(),
                        TextInput::make('saldo')
                            ->label('Net Change (Selisih)')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->default(0)
                            ->formatStateUsing(fn($state) => self::formatNominal($state))
                            ->disabled()
                            ->dehydrated(true)
                            ->dehydrateStateUsing(fn($state) => self::parseNominal($state))
                            ->required(),
                    ]),
                Textarea::make('keterangan')
                    ->label('Uraian / Keterangan')
                    ->placeholder('Contoh: Penerimaan Penjualan, Pembelian ATK, dll')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    private static function updateTanggalDefault($state, $set): void
    {
        if (!$state) return;
        $laporan = \App\Models\Laporan::find($state);
        if ($laporan) {
            $set('tanggal', "{$laporan->tahun}-" . str_pad($laporan->bulan, 2, '0', STR_PAD_LEFT) . "-01");
        }
    }

    private static function updateSaldo(callable $set, callable $get): void
    {
        $penerimaan = self::parseNominal($get('penerimaan'));
        $pengeluaran = self::parseNominal($get('pengeluaran'));

        $set('saldo', self::formatNominal($penerimaan - $pengeluaran));
    }

    private static function parseNominal(mixed $value): float
    {
        if (blank($value)) {
            return 0;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);

        if (str_contains($value, ',')) {
            return (float) str_replace(',', '.', str_replace('.', '', $value));
        }

        if (preg_match('/^-?\d+\.\d{1,2}$/', $value) === 1) {
            return (float) $value;
        }

        return (float) str_replace('.', '', $value);
    }

    private static function formatNominal(mixed $value): string
    {
        return number_format(self::parseNominal($value), 0, ',', '.');
    }
}
