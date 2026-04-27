<?php

namespace App\Filament\Resources\LaporanKeuangan;

use App\Filament\Resources\LaporanKeuangan\Pages\ListLaporanKeuangan;
use App\Filament\Resources\LaporanKeuangan\Schemas\LaporanKeuanganForm;
use App\Filament\Resources\LaporanKeuangan\Tables\LaporanKeuanganTable;
use App\Models\LaporanKeuangan;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaporanKeuanganResource extends Resource
{
    protected static ?string $model = LaporanKeuangan::class;

    protected static ?string $modelLabel = 'Keuangan';
    protected static ?string $pluralModelLabel = 'Laporan Keuangan';

    protected static ?string $slug = 'laporan-keuangan';

    protected static ?string $navigationLabel = 'Laporan Keuangan';
    protected static ?int $navigationSort = 5;
    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'sumber_dana';

    protected static bool $isScopedToTenant = false;

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public static function form(Schema $schema): Schema
    {
        return LaporanKeuanganForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LaporanKeuanganTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('laporan', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()?->id);
            });
    }
    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => ListLaporanKeuangan::route('/'),
        ];
    }
}
