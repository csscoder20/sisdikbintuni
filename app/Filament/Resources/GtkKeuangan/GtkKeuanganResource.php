<?php

namespace App\Filament\Resources\GtkKeuangan;

use App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\Schemas\GtkKeuanganForm;
use App\Filament\Resources\GtkKeuangan\Tables\GtkKeuanganTable;
use App\Models\GtkKeuangan;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class GtkKeuanganResource extends Resource
{
    protected static ?string $slug = 'rekening-npwp-gtk';

    protected static ?string $model = GtkKeuangan::class;

    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Rekening & NPWP';

    protected static ?string $pluralModelLabel = 'Rekening & NPWP';

    // Fixed: Remove the union type syntax, just use string|null

    protected static string | \UnitEnum | null $navigationGroup = 'Data GTK';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static bool $isScopedToTenant = false;

    protected static ?string $recordTitleAttribute = 'NOMINATIF GTK';

    public static function form(Schema $schema): Schema
    {
        return GtkKeuanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GtkKeuanganTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('gtk', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()->id);
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGtkKeuangan::route('/'),
            'create' => Pages\CreateGtkKeuangan::route('/create'),
            'edit' => Pages\EditGtkKeuangan::route('/{record}/edit'),
        ];
    }
}
