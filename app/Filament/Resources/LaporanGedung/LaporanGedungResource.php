<?php

namespace App\Filament\Resources\LaporanGedung;

use App\Filament\Resources\LaporanGedung\Pages\ListLaporanGedung;
use App\Filament\Resources\LaporanGedung\Schemas\LaporanGedungForm;
use App\Filament\Resources\LaporanGedung\Tables\LaporanGedungTable;
use App\Models\Laporan;
use App\Models\LaporanGedung;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use App\Filament\Traits\HasDinasFilter;

class LaporanGedungResource extends Resource
{
    use HasDinasFilter;
    protected static ?string $model = LaporanGedung::class;

    protected static ?string $modelLabel = 'Sarana & Prasarana';
    protected static ?string $pluralModelLabel = 'Sarana & Prasarana';

    protected static ?string $slug = 'laporan-gedung';

    protected static ?string $navigationLabel = 'Sarana & Prasarana';
    protected static ?int $navigationSort = 2;
    protected static string | \UnitEnum | null $navigationGroup = 'Data Sekolah';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama_ruang';

    protected static bool $isScopedToTenant = false;

    public static function canViewAny(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['operator', 'super_admin', 'admin_dinas', 'pengawas']));
    }




    public static function form(Schema $schema): Schema
    {
        return LaporanGedungForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('nama_ruang')->label('Nama Ruang')->prefix(': ')->placeholder('-'),
                TextEntry::make('status_kepemilikan')->label('Status Kepemilikan')->prefix(': ')->placeholder('-'),
                TextEntry::make('jumlah_total')->label('Jumlah Total')->prefix(': ')->placeholder('-'),
                TextEntry::make('jumlah_baik')->label('Jumlah Baik')->prefix(': ')->placeholder('-'),
                TextEntry::make('jumlah_rusak')->label('Jumlah Rusak')->prefix(': ')->placeholder('-'),
                TextEntry::make('periode_laporan')
                    ->label('Periode Laporan')
                    ->state(fn(LaporanGedung $record): ?string => $record->laporan ? "Tahun {$record->laporan->tahun} - Bulan {$record->laporan->bulan}" : null)
                    ->prefix(': ')
                    ->placeholder('-'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return LaporanGedungTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $sekolahId = null;
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            $sekolahId = session('dinas_selected_sekolah_id');
        } else {
            $sekolahId = Filament::getTenant()?->id;
        }

        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereHas('laporan', function (Builder $query) use ($sekolahId) {
                if ($sekolahId) {
                    $query->where('sekolah_id', $sekolahId);
                }
            });

        if (! $sekolahId) {
            return $query;
        }

        $latestLaporanId = Laporan::where('sekolah_id', $sekolahId)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->value('id');

        return $latestLaporanId
            ? $query->where('laporan_id', $latestLaporanId)
            : $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
            'index' => ListLaporanGedung::route('/'),
        ];
    }
}
