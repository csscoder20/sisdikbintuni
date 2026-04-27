<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Pages;

use App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;


class ViewGtkRiwayatPendidikan extends ViewRecord
{
    protected static string $resource = GtkRiwayatPendidikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            EditAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
