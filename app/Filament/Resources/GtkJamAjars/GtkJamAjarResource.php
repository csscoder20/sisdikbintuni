<?php

namespace App\Filament\Resources\GtkJamAjars;

use App\Filament\Resources\GtkJamAjars\Pages\CreateGtkJamAjar;
use App\Filament\Resources\GtkJamAjars\Pages\EditGtkJamAjar;
use App\Filament\Resources\GtkJamAjars\Pages\ListGtkJamAjars;
use App\Filament\Resources\GtkJamAjars\Pages\ViewGtkJamAjar;
use App\Filament\Resources\GtkJamAjars\Schemas\GtkJamAjarForm;
use App\Filament\Resources\GtkJamAjars\Schemas\GtkJamAjarInfolist;
use App\Filament\Resources\GtkJamAjars\Tables\GtkJamAjarsTable;
use App\Models\GtkJamAjar;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GtkJamAjarResource extends Resource
{
    protected static ?string $model = GtkJamAjar::class;

    protected static ?string $modelLabel = 'GTK Jam Ajar';

    protected static ?string $pluralModelLabel = 'GTK Jam Ajar';

    protected static string | \UnitEnum | null $navigationGroup = 'Warga Belajar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'GTK Jam AJar';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'operator';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->role === 'operator') {
            // No current filter
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return GtkJamAjarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GtkJamAjarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GtkJamAjarsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGtkJamAjars::route('/'),
            'create' => CreateGtkJamAjar::route('/create'),
            'view' => ViewGtkJamAjar::route('/{record}'),
            'edit' => EditGtkJamAjar::route('/{record}/edit'),
        ];
    }
}
