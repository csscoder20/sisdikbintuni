<?php

namespace App\Filament\Resources\GtkKeuangan\Pages;

use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGtkKeuangans extends ListRecords
{
    protected static string $resource = GtkKeuanganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No CreateAction because we only edit existing GTK records
        ];
    }
}
