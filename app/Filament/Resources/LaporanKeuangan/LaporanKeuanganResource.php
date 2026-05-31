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

use App\Filament\Traits\HasDinasFilter;

class LaporanKeuanganResource extends Resource
{
    use HasDinasFilter;
    protected static ?string $model = LaporanKeuangan::class;

    protected static ?string $modelLabel = 'Keuangan';
    protected static ?string $pluralModelLabel = 'Keuangan';

    protected static ?string $slug = 'laporan-keuangan';

    protected static ?string $navigationLabel = 'Keuangan';
    protected static ?int $navigationSort = 5;
    protected static string | \UnitEnum | null $navigationGroup = 'Data Sekolah';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'keterangan';

    protected static bool $isScopedToTenant = false;

    public static function canViewAny(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['operator', 'super_admin', 'admin_dinas', 'pengawas']));
    }


    public static function form(Schema $schema): Schema
    {
        return LaporanKeuanganForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('tanggal')
                    ->label('Tanggal Transaksi')
                    ->date('d F Y')
                    ->prefix(': ')
                    ->placeholder('-'),
                TextEntry::make('jenis_transaksi')
                    ->label('Jenis Transaksi')
                    ->prefix(': ')
                    ->placeholder('-')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'debit' ? 'Debit (Uang Masuk)' : 'Kredit (Uang Keluar)')
                    ->color(fn($state) => $state === 'debit' ? 'success' : 'danger'),
                TextEntry::make('nominal')
                    ->label('Nominal')
                    ->prefix(': ')
                    ->money('idr')
                    ->placeholder('-'),
                TextEntry::make('laporan.bulan')
                    ->label('Periode')
                    ->prefix(': ')
                    ->placeholder('-')
                    ->formatStateUsing(function ($record) {
                        if (!$record->laporan) return '-';
                        $namaBulan = \Carbon\Carbon::create()->month($record->laporan->bulan)->translatedFormat('F');
                        return "{$namaBulan} {$record->laporan->tahun}";
                    }),
                TextEntry::make('keterangan')
                    ->label('Keterangan')
                    ->prefix(': ')
                    ->placeholder('-'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return LaporanKeuanganTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return static::scopeQueryToCurrentTenant(parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    protected static function scopeQueryToCurrentTenant(Builder $query): Builder
    {
        $sekolahId = null;
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            $sekolahId = session('dinas_selected_sekolah_id');
        } else {
            $sekolahId = Filament::getTenant()?->id;
        }

        return $query
            ->whereHas('laporan', function (Builder $query) use ($sekolahId) {
                if ($sekolahId) {
                    $query->where('sekolah_id', $sekolahId);
                }
            });
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return static::scopeQueryToCurrentTenant(parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLaporanKeuangan::route('/'),
        ];
    }
}
