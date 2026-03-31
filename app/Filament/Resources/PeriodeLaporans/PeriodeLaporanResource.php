<?php

namespace App\Filament\Resources\PeriodeLaporans;

use App\Filament\Resources\PeriodeLaporans\Pages\CreatePeriodeLaporan;
use App\Filament\Resources\PeriodeLaporans\Pages\EditPeriodeLaporan;
use App\Filament\Resources\PeriodeLaporans\Pages\ListPeriodeLaporans;
use App\Filament\Resources\PeriodeLaporans\Pages\ViewPeriodeLaporan;
use App\Filament\Resources\PeriodeLaporans\Schemas\PeriodeLaporanForm;
use App\Filament\Resources\PeriodeLaporans\Schemas\PeriodeLaporanInfolist;
use App\Filament\Resources\PeriodeLaporans\Tables\PeriodeLaporansTable;
use App\Models\PeriodeLaporan;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PeriodeLaporanResource extends Resource
{
    protected static ?string $model = PeriodeLaporan::class;

    protected static ?string $modelLabel = 'Periode Laporan';

    protected static ?string $pluralModelLabel = 'Periode Laporan';

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'Periode Laporan';

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
        return PeriodeLaporanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PeriodeLaporanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PeriodeLaporansTable::configure($table);
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
            'index' => ListPeriodeLaporans::route('/'),
            'create' => CreatePeriodeLaporan::route('/create'),
            'view' => ViewPeriodeLaporan::route('/{record}'),
            'edit' => EditPeriodeLaporan::route('/{record}/edit'),
        ];
    }
}
