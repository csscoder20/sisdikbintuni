<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Pages;

use App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource;
use Filament\Resources\Pages\ViewRecord;

class ViewGtkRiwayatPendidikan extends ViewRecord
{
    protected static string $resource = GtkRiwayatPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
