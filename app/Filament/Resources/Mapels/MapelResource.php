<?php

namespace App\Filament\Resources\Mapels;

use App\Filament\Resources\Mapels\Pages\ListMapels;
use App\Filament\Resources\Mapels\Schemas\MapelForm;
use App\Filament\Resources\Mapels\Tables\MapelsTable;
use App\Models\Mapel;
use BackedEnum;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MapelResource extends Resource
{
    protected static ?string $slug = 'mapel';

    protected static ?string $model = Mapel::class;

    protected static ?string $modelLabel = 'Mapel';

    protected static ?string $pluralModelLabel = 'Mapel';

    protected static ?int $navigationSort = 3;

    protected static string | \UnitEnum | null $navigationGroup = 'Data Sekolah';

    public static function shouldRegisterNavigation(): bool
    {
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            return !empty(session('dinas_selected_sekolah_id'));
        }
        return true;
    }

    // NON AKTIFKAN TENANCY
    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function canViewAny(): bool
    {
        return auth()->check() && (auth()->user()->hasRole(['operator', 'super_admin', 'admin_dinas', 'pengawas']));
    }


    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(Model $record): bool
    {
        return true;
    }

    public static function canView(Model $record): bool
    {
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return MapelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MapelsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $jenjang = null;
        $sekolahId = null;

        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            $selectedId = session('dinas_selected_sekolah_id');
            if ($selectedId) {
                $sekolah = \App\Models\Sekolah::find($selectedId);
                $jenjang = $sekolah?->jenjang;
                $sekolahId = $selectedId;
            }
        } else {
            $tenant = Filament::getTenant();
            $panelId = Filament::getCurrentPanel()?->getId();
            $jenjang = $tenant?->jenjang ?: (in_array($panelId, ['sma', 'smk']) ? strtoupper($panelId) : null);
            $sekolahId = $tenant?->id;
        }

        if ($jenjang) {
            if ($sekolahId) {
                $query->where(function ($q) use ($jenjang, $sekolahId) {
                    $q->whereNull('sekolah_id')
                      ->where('jenjang', strtolower($jenjang))
                      ->orWhere('sekolah_id', $sekolahId);
                });
            } else {
                $query->where('jenjang', strtolower($jenjang));
            }
        }

        return $query;
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
            'index' => ListMapels::route('/'),
        ];
    }
}
