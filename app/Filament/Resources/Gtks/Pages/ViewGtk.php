<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use Filament\Resources\Pages\ViewRecord;

class ViewGtk extends ViewRecord
{
    protected static string $resource = GtkResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
