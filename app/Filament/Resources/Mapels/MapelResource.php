<?php

namespace App\Filament\Resources\Mapels;

use App\Filament\Resources\Mapels\Pages\ListMapels;
use App\Filament\Resources\Mapels\Schemas\MapelForm;
use App\Filament\Resources\Mapels\Tables\MapelsTable;
use App\Models\Mapel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MapelResource extends Resource
{
    protected static ?string $slug = 'mapel';

    protected static ?string $model = Mapel::class;

    protected static ?string $modelLabel = 'Mapel';

    protected static ?string $pluralModelLabel = 'Mapel';

    protected static ?int $navigationSort = 3;

    protected static string | \UnitEnum | null $navigationGroup = 'Data Sekolah';

    // NON AKTIFKAN TENANCY
    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canView(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return MapelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MapelsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if ($tenant = Filament::getTenant()) {
            $panel = Filament::getCurrentPanel();
            $panelId = $panel ? $panel->getId() : null;
            $jenjang = $tenant->jenjang ?: (in_array($panelId, ['sma', 'smk']) ? strtoupper($panelId) : null);
            
            if ($jenjang) {
                $query->where('jenjang', $jenjang);
            }
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMapels::route('/'),
        ];
    }
}
