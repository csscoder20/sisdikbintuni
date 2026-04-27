<?php

namespace App\Filament\Resources\GtkJamAjars\Pages;

use App\Filament\Resources\GtkJamAjars\GtkJamAjarResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;


class ViewGtkJamAjar extends ViewRecord
{
    protected static string $resource = GtkJamAjarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            EditAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
