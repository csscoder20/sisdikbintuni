<?php

namespace App\Filament\Resources\CetakLaporan;

use App\Models\Sekolah;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Facades\Filament;
use App\Filament\Traits\HasDinasFilter;
use App\Filament\Resources\CetakLaporan\Pages;

class CetakLaporanResource extends Resource
{
    use HasDinasFilter;

    protected static ?string $model = Sekolah::class;

    protected static ?string $slug = 'cetak-laporan-bulanan';

    protected static ?string $navigationLabel = 'Cetak Laporan Bulanan';

    protected static ?string $modelLabel = 'Cetak Laporan';

    protected static ?string $pluralModelLabel = 'Cetak Laporan Bulanan';

    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Cetak';

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedPrinter;

    public static function canViewAny(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['super_admin', 'admin_dinas']));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('npsn')
                    ->label('NPSN')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('jenjang')
                    ->label('Jenjang')
                    ->badge()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),
                TextColumn::make('kecamatan')
                    ->label('Kecamatan')
                    ->searchable(),
            ])
            ->actions([
                Action::make('cetak')
                    ->label('Cetak Laporan')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (Sekolah $record) => "#") // Placeholder for PDF route
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCetakLaporans::route('/'),
        ];
    }
}
