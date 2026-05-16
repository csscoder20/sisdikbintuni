<?php

namespace App\Filament\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

trait HasDinasFilter
{
    public static function shouldRegisterNavigation(): bool
    {
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            $selectedId = session('dinas_selected_sekolah_id');
            return !empty($selectedId);
        }

        return parent::shouldRegisterNavigation();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                \Illuminate\Database\Eloquent\SoftDeletingScope::class,
            ]);

        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            $selectedSekolahId = session('dinas_selected_sekolah_id');
            
            if ($selectedSekolahId) {
                $model = static::getModel();
                $tableName = (new $model)->getTable();
                
                if ($model === \App\Models\Sekolah::class) {
                    $query->where($tableName . '.id', $selectedSekolahId);
                } else {
                    $query->where($tableName . '.sekolah_id', $selectedSekolahId);
                }
            } else {
                // Force empty result if no school is selected in Dinas panel
                $query->whereRaw('1 = 0');
            }
        }

        return $query;
    }

    public static function isScopedToTenant(): bool
    {
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            return false;
        }

        return true;
    }
}
