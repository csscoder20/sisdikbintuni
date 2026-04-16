<?php

namespace App\Filament\Resources\Siswas;

use App\Filament\Resources\Siswas\Pages\ListSiswas;
use App\Filament\Resources\Siswas\Schemas\SiswaForm;
use App\Filament\Resources\Siswas\Tables\SiswasTable;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum; // Add this import

class SiswaResource extends Resource
{
    protected static ?string $slug = 'siswa';

    protected static ?string $model = Siswa::class;

    protected static ?string $navigationLabel = 'Nominatif Siswa';

    protected static ?int $navigationSort = 1;

    protected static string | \UnitEnum | null $navigationGroup = 'Data Siswa';

    protected static ?string $modelLabel = 'Nominatif Siswa';

    protected static ?string $pluralModelLabel = 'Nominatif Siswa';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static bool $isScopedToTenant = true;

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }



    public static function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }



    public static function table(Table $table): Table
    {
        return SiswasTable::configure($table);
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
            'index' => ListSiswas::route('/'),
        ];
    }
}