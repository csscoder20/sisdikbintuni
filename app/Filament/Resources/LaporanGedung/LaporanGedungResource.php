<?php

namespace App\Filament\Resources\LaporanGedung;

use App\Filament\Resources\LaporanGedung\Pages\ListLaporanGedung;
use App\Filament\Resources\LaporanGedung\Schemas\LaporanGedungForm;
use App\Filament\Resources\LaporanGedung\Tables\LaporanGedungTable;
use App\Models\LaporanGedung;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanGedungResource extends Resource
{
    protected static ?string $model = LaporanGedung::class;
    
    protected static ?string $modelLabel = 'Keadaan Gedung/Ruang';
    protected static ?string $pluralModelLabel = 'Keadaan Gedung/Ruang';
    
    protected static ?string $slug = 'laporan-gedung';
    
    protected static ?string $navigationLabel = 'Keadaan Gedung/Ruang';
    protected static ?int $navigationSort = 1;
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_ruang';

    protected static bool $isScopedToTenant = false;

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }



    public static function form(Schema $schema): Schema
    {
        return LaporanGedungForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanGedungTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('laporan', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()->id);
            });
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
            'index' => ListLaporanGedung::route('/'),
        ];
    }
}
