<?php

namespace App\Filament\Resources\LaporanGtk;

use App\Filament\Resources\LaporanGtk\Pages;
use App\Filament\Resources\LaporanGtk\Schemas\LaporanGtkForm;
use App\Filament\Resources\LaporanGtk\Tables\LaporanGtkTable;
use App\Models\LaporanGtk;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class LaporanGtkResource extends Resource
{
    protected static ?string $slug = 'laporan-gtk';

    protected static ?string $model = LaporanGtk::class;
    protected static bool $isScopedToTenant = false;



    protected static ?string $modelLabel = 'Detail Laporan GTK';

    protected static ?string $pluralModelLabel = 'Detail Laporan GTK';


    // Fixed: Remove the union type syntax, just use string|null

    protected static string | \UnitEnum | null $navigationGroup = 'Laporan Bulanan';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && !auth()->user()->hasRole('operator');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'NOMINATIF GTK';


    public static function form(Schema $schema): Schema
    {
        return LaporanGtkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Section::make('Ringkasan Data')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('gtk.nama')->label('Nama GTK')->prefix(': ')->placeholder('-'),
                        TextEntry::make('periode_laporan')
                            ->label('Periode Laporan')
                            ->state(fn (LaporanGtk $record): ?string => $record->laporan ? "{$record->laporan->bulan}/{$record->laporan->tahun}" : null)
                            ->prefix(': ')
                            ->placeholder('-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return LaporanGtkTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereHas('laporan', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()->id);
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
            'index' => Pages\ListLaporanGtk::route('/'),
        ];
    }
}
