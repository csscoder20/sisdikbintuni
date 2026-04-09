<?php

namespace App\Filament\Resources\KehadiranGtk;

use App\Filament\Resources\KehadiranGtk\Pages;
use App\Filament\Resources\KehadiranGtk\Schemas\KehadiranGtkForm;
use App\Filament\Resources\KehadiranGtk\Tables\KehadiranGtkTable;
use App\Models\KehadiranGtk;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class KehadiranGtkResource extends Resource
{
    protected static ?string $model = KehadiranGtk::class;



    protected static ?string $modelLabel = 'Kehadiran GTK';

    protected static ?string $pluralModelLabel = 'Kehadiran GTK';

    protected static ?string $navigationLabel = 'Kehadiran GTK';

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    protected static ?int $navigationSort = 5;

    protected static bool $isScopedToTenant = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'NOMINATIF GTK';

    public static function form(Schema $schema): Schema
    {
        return KehadiranGtkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KehadiranGtkTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKehadiranGtk::route('/'),
            'create' => Pages\CreateKehadiranGtk::route('/create'),
            'edit' => Pages\EditKehadiranGtk::route('/{record}/edit'),
        ];
    }
}
