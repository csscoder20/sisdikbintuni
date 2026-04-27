<?php

namespace App\Filament\Resources\LaporanSiswa;

use App\Filament\Resources\LaporanSiswa\Pages;
use App\Filament\Resources\LaporanSiswa\Schemas\LaporanSiswaForm;
use App\Filament\Resources\LaporanSiswa\Tables\LaporanSiswaTable;
use App\Models\LaporanSiswa;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class LaporanSiswaResource extends Resource
{
    protected static ?string $slug = 'laporan-siswa';

    protected static ?string $model = LaporanSiswa::class;

    protected static ?string $modelLabel = 'Detail Laporan Siswa';

    protected static ?string $pluralModelLabel = 'Detail Laporan Siswa';

    // Gunakan tipe yang benar untuk navigationGroup
    protected static string|\UnitEnum|null $navigationGroup = 'Laporan Bulanan';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && !auth()->user()->hasRole('operator');
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'id';

    // Matikan multitenancy untuk resource ini
    protected static ?string $tenantOwnershipRelationshipName = null;

    // Tambahkan ini untuk menonaktifkan scoping ke tenant
    protected static bool $isScopedToTenant = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('laporan', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()->id);
            });
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public static function form(Schema $schema): Schema
    {
        return LaporanSiswaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                Section::make('Ringkasan Data')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('rombel.nama')->label('Rombel')->placeholder('-'),
                        TextEntry::make('periode_laporan')
                            ->label('Periode Laporan')
                            ->state(fn (LaporanSiswa $record): ?string => $record->laporan ? "{$record->laporan->bulan}/{$record->laporan->tahun}" : null)
                            ->placeholder('-'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return LaporanSiswaTable::configure($table);
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
            'index' => Pages\ListLaporanSiswa::route('/'),
        ];
    }
}
