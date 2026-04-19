<?php

namespace App\Filament\Resources\Laporan;

use App\Filament\Resources\Laporan\Pages;
use App\Filament\Resources\Laporan\Schemas\LaporanForm;
use App\Filament\Resources\Laporan\Tables\LaporanTable;
use App\Models\Laporan;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class LaporanResource extends Resource
{
    protected static ?string $slug = 'laporan';

    protected static ?string $model = Laporan::class;


    protected static ?string $modelLabel = 'Laporan Bulanan';

    protected static ?string $pluralModelLabel = 'Laporan Bulanan';


    // Fixed: Remove the union type syntax, just use string|null

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && !auth()->user()->hasRole('operator');
    }

    protected static bool $isScopedToTenant = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $recordTitleAttribute = 'Laporan Bulanan';

    public static function form(Schema $schema): Schema
    {
        return LaporanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Section::make('Ringkasan Data')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('tahun')->label('Tahun')->placeholder('-'),
                        TextEntry::make('bulan')
                            ->label('Bulan')
                            ->formatStateUsing(fn ($state): string => match ((string) $state) {
                                '1' => 'Januari',
                                '2' => 'Februari',
                                '3' => 'Maret',
                                '4' => 'April',
                                '5' => 'Mei',
                                '6' => 'Juni',
                                '7' => 'Juli',
                                '8' => 'Agustus',
                                '9' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember',
                                default => (string) $state,
                            }),
                        TextEntry::make('sekolah.nama')->label('Sekolah')->placeholder('-'),
                        TextEntry::make('created_at')->label('Dibuat Pada')->dateTime('d/m/Y H:i')->placeholder('-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return LaporanTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporan::route('/'),
        ];
    }
}
