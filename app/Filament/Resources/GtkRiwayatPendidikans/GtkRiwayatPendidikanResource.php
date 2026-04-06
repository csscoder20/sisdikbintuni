<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans;

use App\Filament\Resources\GtkRiwayatPendidikans\Pages\CreateGtkRiwayatPendidikan;
use App\Filament\Resources\GtkRiwayatPendidikans\Pages\EditGtkRiwayatPendidikan;
use App\Filament\Resources\GtkRiwayatPendidikans\Pages\ListGtkRiwayatPendidikans;
use App\Filament\Resources\GtkRiwayatPendidikans\Pages\ViewGtkRiwayatPendidikan;
use App\Filament\Resources\GtkRiwayatPendidikans\Schemas\GtkRiwayatPendidikanForm;
use App\Filament\Resources\GtkRiwayatPendidikans\Schemas\GtkRiwayatPendidikanInfolist;
use App\Filament\Resources\GtkRiwayatPendidikans\Tables\GtkRiwayatPendidikansTable;
use App\Models\GtkRiwayatPendidikan;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GtkRiwayatPendidikanResource extends Resource
{
    protected static ?string $model = GtkRiwayatPendidikan::class;

    protected static ?string $modelLabel = 'GTK RIWAYAT PENDIDIKAN';

    protected static ?string $pluralModelLabel = 'GTK RIWAYAT PENDIDIKAN';

    // protected static ?string $navigationLabel = 'KEADAAN SARPRAS';
    protected static ?int $navigationSort = 5;
    protected static string | \UnitEnum | null $navigationGroup = 'LAPORAN BULANAN';


    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'GTK RIWAYAT PENDIDIKAN';

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'operator';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->role === 'operator') {
            return $query->whereHas('gtk', function ($q) {
                $q->where('id_sekolah', auth()->user()->sekolah_id);
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return GtkRiwayatPendidikanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GtkRiwayatPendidikanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GtkRiwayatPendidikansTable::configure($table);
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
            'index' => ListGtkRiwayatPendidikans::route('/'),
            'create' => CreateGtkRiwayatPendidikan::route('/create'),
            'view' => ViewGtkRiwayatPendidikan::route('/{record}'),
            'edit' => EditGtkRiwayatPendidikan::route('/{record}/edit'),
        ];
    }
}
