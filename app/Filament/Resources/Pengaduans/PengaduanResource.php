<?php

namespace App\Filament\Resources\Pengaduans;

use App\Filament\Resources\Pengaduans\Pages\ListPengaduans;
use App\Filament\Resources\Pengaduans\Schemas\PengaduanForm;
use App\Filament\Resources\Pengaduans\Schemas\PengaduanInfolist;
use App\Filament\Resources\Pengaduans\Tables\PengaduansTable;
use App\Models\Pengaduan;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static string | \UnitEnum | null $navigationGroup = 'Sistem';
    protected static ?string $navigationLabel = 'Pengaduan/Tiket';
    protected static ?string $pluralModelLabel = 'Pengaduan/Tiket';
    protected static ?int $navigationSort = 4;
    // Disable tenancy injection so it won't fail if we don't have scope defined perfectly
    protected static bool $isScopedToTenant = false;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        if (!auth()->check()) {
            return false;
        }
        $user = auth()->user();
        // Super admin bisa buat
        if ($user->hasRole('super_admin')) {
            return true;
        }
        // Operator sekolah bisa buat
        if ($user->hasRole('operator')) {
            return true;
        }
        // Admin dinas tidak bisa buat
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PengaduanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PengaduanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PengaduansTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $panelId = Filament::getCurrentPanel()?->getId();
        if (in_array($panelId, ['sma', 'smk'])) {
            $sekolahId = auth()->user()->sekolah_id;
            if ($sekolahId) {
                $query->where('sekolah_id', $sekolahId);
            } else {
                $query->where('user_id', auth()->id());
            }
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPengaduans::route('/'),
        ];
    }
}
