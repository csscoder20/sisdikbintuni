<?php

namespace App\Traits;

use Filament\Facades\Filament;

trait HasSchoolContext
{
    protected static function bootHasSchoolContext()
    {
        static::creating(function ($model) {
            // Only inject if sekolah_id is empty and we are in the dinas panel
            if (empty($model->sekolah_id) && Filament::getCurrentPanel()?->getId() === 'dinas') {
                $selectedSekolahId = session('dinas_selected_sekolah_id');
                if ($selectedSekolahId) {
                    $model->sekolah_id = $selectedSekolahId;
                }
            }
        });
    }
}
