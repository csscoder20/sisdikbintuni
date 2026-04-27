<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;


class ViewGtk extends ViewRecord
{
    protected static string $resource = GtkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            EditAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
