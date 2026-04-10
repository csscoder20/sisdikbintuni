<?php

namespace App\Filament\Resources\Kelulusan;

use App\Filament\Resources\Kelulusan\Pages;
use App\Filament\Resources\Kelulusan\Schemas\KelulusanForm;
use App\Filament\Resources\Kelulusan\Tables\KelulusanTable;
use App\Models\Kelulusan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class KelulusanResource extends Resource
{
    protected static ?string $slug = 'kelulusan';

    protected static ?string $model = Kelulusan::class;

    protected static ?string $modelLabel = 'Kelulusan';

    protected static ?string $pluralModelLabel = 'Kelulusan';


    // Fixed: Remove the union type syntax, just use string|null

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    protected static ?int $navigationSort = 6;

    protected static bool $isScopedToTenant = true;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'Data Kelulusan';

    public static function form(Schema $schema): Schema
    {
        return KelulusanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelulusanTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelulusan::route('/'),
            'create' => Pages\CreateKelulusan::route('/create'),
            'edit' => Pages\EditKelulusan::route('/{record}/edit'),
        ];
    }
}
