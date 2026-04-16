<?php

namespace App\Filament\Resources\Laporan;

use App\Filament\Resources\Laporan\Pages;
use App\Filament\Resources\Laporan\Schemas\LaporanForm;
use App\Filament\Resources\Laporan\Tables\LaporanTable;
use App\Models\Laporan;
use Filament\Resources\Resource;
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
