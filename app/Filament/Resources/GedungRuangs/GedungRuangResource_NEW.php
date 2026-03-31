<?php

namespace App\Filament\Resources\GedungRuangs;

use App\Filament\Resources\GedungRuangs\Pages\CreateGedungRuang;
use App\Filament\Resources\GedungRuangs\Pages\EditGedungRuang;
use App\Filament\Resources\GedungRuangs\Pages\ListGedungRuangs;
use App\Filament\Resources\GedungRuangs\Pages\ViewGedungRuang;
use App\Filament\Resources\GedungRuangs\Schemas\GedungRuangForm;
use App\Filament\Resources\GedungRuangs\Schemas\GedungRuangInfolist;
use App\Filament\Resources\GedungRuangs\Tables\GedungRuangsTable;
use App\Models\GedungRuang;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GedungRuangResource extends Resource
{
    protected static ?string $model = GedungRuang::class;

    protected static ?string $modelLabel = 'Gedung & Ruang';

    protected static ?string $pluralModelLabel = 'Gedung & Ruang';

    protected static ?string $navigationGroup = 'Sekolah';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static ?string $recordTitleAttribute = 'GedungRuang';

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
        return GedungRuangForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GedungRuangInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GedungRuangsTable::configure($table);
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
            'index' => ListGedungRuangs::route('/'),
            'create' => CreateGedungRuang::route('/create'),
            'view' => ViewGedungRuang::route('/{record}'),
            'edit' => EditGedungRuang::route('/{record}/edit'),
        ];
    }
}
