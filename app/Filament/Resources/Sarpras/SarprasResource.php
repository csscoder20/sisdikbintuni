<?php

namespace App\Filament\Resources\Sarpras;

use App\Filament\Resources\Sarpras\Pages\CreateSarpras;
use App\Filament\Resources\Sarpras\Pages\EditSarpras;
use App\Filament\Resources\Sarpras\Pages\ListSarpras;
use App\Filament\Resources\Sarpras\Schemas\SarprasForm;
use App\Filament\Resources\Sarpras\Tables\SarprasTable;
use App\Models\Sarpras;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SarprasResource extends Resource
{
    protected static ?string $model = Sarpras::class;
    protected static ?string $navigationLabel = 'KEADAAN SARPRAS';
    protected static ?int $navigationSort = 1;
    protected static string | \UnitEnum | null $navigationGroup = 'LAPORAN BULANAN';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_gedung_ruang';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'operator';
    }

    public static function form(Schema $schema): Schema
    {
        return SarprasForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SarprasTable::configure($table);
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
            'index' => ListSarpras::route('/'),
            'create' => CreateSarpras::route('/create'),
            'edit' => EditSarpras::route('/{record}/edit'),
        ];
    }
}
