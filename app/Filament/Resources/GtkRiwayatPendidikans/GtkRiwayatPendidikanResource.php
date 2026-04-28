<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans;

use App\Filament\Resources\GtkRiwayatPendidikans\Pages\ListGtkRiwayatPendidikans;
use App\Filament\Resources\GtkRiwayatPendidikans\Schemas\GtkRiwayatPendidikanForm;
use App\Filament\Resources\GtkRiwayatPendidikans\Tables\GtkRiwayatPendidikansTable;
use App\Models\GtkPendidikan;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GtkRiwayatPendidikanResource extends Resource
{
    protected static ?string $slug = 'riwayat-pendidikan-gtk';

    protected static ?string $model = GtkPendidikan::class;

    protected static ?string $modelLabel = 'Riwayat Pendidikan GTK';

    protected static ?string $pluralModelLabel = 'Riwayat Pendidikan GTK';

    protected static ?int $navigationSort = 2;

    protected static string | \UnitEnum | null $navigationGroup = 'Data GTK';

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'GTK RIWAYAT PENDIDIKAN';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('operator');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return GtkRiwayatPendidikanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel()
            ->components([
                TextEntry::make('gtk.nama')
                ->label('Nama GTK')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('gelar_depan')
                ->label('Gelar Depan')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('gelar_belakang')
                ->label('Gelar Belakang')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_sd')
                ->label('Tahun Tamat SD')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_smp')
                ->label('Tahun Tamat SMP')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_sma')
                ->label('Tahun Tamat SMA')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_d1')
                ->label('Tahun Tamat D1')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('jurusan_d1')
                ->label('Jurusan D1')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('perguruan_tinggi_d1')
                ->label('Perguruan Tinggi D1')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_d2')
                ->label('Tahun Tamat D2')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('jurusan_d2')
                ->label('Jurusan D2')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('perguruan_tinggi_d2')
                ->label('Perguruan Tinggi D2')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_d3')
                ->label('Tahun Tamat D3')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('jurusan_d3')
                ->label('Jurusan D3')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('perguruan_tinggi_d3')
                ->label('Perguruan Tinggi D3')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_s1')
                ->label('Tahun Tamat S1')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('jurusan_s1')
                ->label('Jurusan S1')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('perguruan_tinggi_s1')
                ->label('Perguruan Tinggi S1')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_s2')
                ->label('Tahun Tamat S2')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('jurusan_s2')
                ->label('Jurusan S2')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('perguruan_tinggi_s2')
                ->label('Perguruan Tinggi S2')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_tamat_s3')
                ->label('Tahun Tamat S3')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('jurusan_s3')
                ->label('Jurusan S3')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('perguruan_tinggi_s3')
                ->label('Perguruan Tinggi S3')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('thn_akta4')
                ->label('Tahun Tamat Akta IV')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('jurusan_akta4')
                ->label('Jurusan Akta IV')
                ->prefix(': ')
                ->placeholder('-'),
                TextEntry::make('perguruan_tinggi_akta4')
                ->label('Perguruan Tinggi Akta IV')
                ->prefix(': ')
                ->placeholder('-'),
            ])->columns(2);
    }



    public static function table(Table $table): Table
    {
        return GtkRiwayatPendidikansTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->whereHas('gtk', function (Builder $query) {
                $query->where('sekolah_id', filament()->getTenant()->id);
            });
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
            'index' => ListGtkRiwayatPendidikans::route('/'),
        ];
    }
}
