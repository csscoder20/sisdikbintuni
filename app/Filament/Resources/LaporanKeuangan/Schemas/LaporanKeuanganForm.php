<?php

namespace App\Filament\Resources\LaporanKeuangan\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
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
                Hidden::make('laporan_id')
                    ->default(fn($get) => self::getLaporanIdFromDate(now()->toDateString()))
                    ->required()
                    ->dehydrated(),
                Grid::make(2)
                    ->schema([
                        DatePicker::make('tanggal')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->default(now())
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $set('laporan_id', self::getLaporanIdFromDate($state));
                                }
                            }),
                        Select::make('jenis_transaksi')
                            ->label('Jenis Transaksi')
                            ->options([
                                'kredit' => 'Kredit (Uang Masuk)',
                                'debit' => 'Debit (Uang Keluar)',
                            ])
                            ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('Contoh: Penerimaan dana, pembelian ATK, dll')
                            ->rows(3)
                            ->columnSpan(1),
                        TextInput::make('nominal')
                            ->label('Nominal')
                            ->placeholder('Rp 0')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->stripCharacters('.')
                            ->extraInputAttributes(['inputmode' => 'numeric'])
                            ->default(0)
                            ->formatStateUsing(fn($state) => self::formatNominal($state))
                            ->dehydrateStateUsing(fn($state) => self::parseNominal($state))
                            ->required(),
                    ]),
            ])->columns(1);
    }

    private static function getLaporanIdFromDate(?string $dateString): ?int
    {
        if (!$dateString) return null;

        try {
            $date = \Carbon\Carbon::parse($dateString);
            $sekolahId = Filament::getTenant()?->id;

            if (!$sekolahId) {
                // Fallback untuk admin dinas yang mungkin sedang mengakses tanpa tenant
                $sekolahId = auth()->user()->sekolah_id;
            }

            if (!$sekolahId) return null;

            // Cari atau buat laporan untuk bulan & tahun tersebut
            return \App\Models\Laporan::firstOrCreate([
                'sekolah_id' => $sekolahId,
                'bulan' => $date->month,
                'tahun' => $date->year,
            ])->id;
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function getLaporanOptions(): array
    {
        $sekolahId = Filament::getTenant()?->id ?? auth()->user()->sekolah_id;
        
        if (!$sekolahId) return [];

        return \App\Models\Laporan::where('sekolah_id', $sekolahId)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get()
            ->mapWithKeys(function ($laporan) {
                $namaBulan = \Carbon\Carbon::create()->month($laporan->bulan)->translatedFormat('F');
                return [$laporan->id => "{$namaBulan} {$laporan->tahun}"];
            })
            ->toArray();
    }

    private static function getLatestLaporanId(): ?int
    {
        return self::getLatestLaporan()?->id;
    }

    private static function getDefaultTanggal(): string
    {
        $laporan = self::getLatestLaporan();

        if (! $laporan) {
            return now()->toDateString();
        }

        return "{$laporan->tahun}-" . str_pad($laporan->bulan, 2, '0', STR_PAD_LEFT) . "-01";
    }

    private static function getLatestLaporan(): ?\App\Models\Laporan
    {
        return \App\Models\Laporan::where('sekolah_id', Filament::getTenant()?->id)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->first();
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
