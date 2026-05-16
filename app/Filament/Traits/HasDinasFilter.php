<?php

namespace App\Filament\Traits;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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

        $sekolahId = null;
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            $sekolahId = session('dinas_selected_sekolah_id');
        } else {
            $sekolahId = Filament::getTenant()?->id;
        }

        if ($sekolahId) {
            $modelClass = static::getModel();
            $model = new $modelClass;
            $tableName = $model->getTable();

            if ($modelClass === \App\Models\Sekolah::class) {
                $query->where($tableName . '.id', $sekolahId);
            } elseif (in_array('sekolah_id', $model->getFillable()) || \Illuminate\Support\Facades\Schema::hasColumn($tableName, 'sekolah_id')) {
                $query->where($tableName . '.sekolah_id', $sekolahId);
            }
        } elseif (Filament::getCurrentPanel()?->getId() === 'dinas') {
            // Force empty result if no school is selected in Dinas panel
            $query->whereRaw('1 = 0');
        }

        return $query;
    }

    public static function isScopedToTenant(): bool
    {
        if (Filament::getCurrentPanel()?->getId() === 'dinas') {
            return false;
        }

        // Detect if the 'sekolah' relationship is a HasOneThrough (read-only for save)
        $modelClass = static::getModel();
        $model = new $modelClass;
        
        if (method_exists($model, 'sekolah')) {
            try {
                $relation = $model->sekolah();
                if ($relation instanceof HasOneThrough) {
                    return false;
                }
            } catch (\Throwable $e) {
                // Skip if relationship call fails
            }
        }

        return true;
    }
}
