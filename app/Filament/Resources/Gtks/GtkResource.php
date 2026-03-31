<?php

namespace App\Filament\Resources\Gtks;

use App\Filament\Resources\Gtks\Pages\CreateGtk;
use App\Filament\Resources\Gtks\Pages\EditGtk;
use App\Filament\Resources\Gtks\Pages\ListGtks;
use App\Filament\Resources\Gtks\Pages\ViewGtk;
use App\Filament\Resources\Gtks\Schemas\GtkForm;
use App\Filament\Resources\Gtks\Schemas\GtkInfolist;
use App\Filament\Resources\Gtks\Tables\GtksTable;
use App\Models\Gtk;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GtkResource extends Resource
{
    protected static ?string $model = Gtk::class;

    protected static ?string $modelLabel = 'GTK';

    protected static ?string $pluralModelLabel = 'GTK';

    protected static string | \UnitEnum | null $navigationGroup = 'Warga Belajar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static ?string $recordTitleAttribute = 'Gtk';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'operator';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->role === 'operator') {
            return $query->where('id_sekolah', auth()->user()->sekolah_id);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return GtkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GtkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GtksTable::configure($table);
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
            'index' => ListGtks::route('/'),
            'create' => CreateGtk::route('/create'),
            'view' => ViewGtk::route('/{record}'),
            'edit' => EditGtk::route('/{record}/edit'),
        ];
    }
}
