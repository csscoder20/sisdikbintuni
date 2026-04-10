<?php

namespace App\Filament\Resources\GtkJamAjars\Pages;

use App\Filament\Resources\GtkJamAjars\GtkJamAjarResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGtkJamAjar extends CreateRecord
{
    protected static bool $canCreateAnother = false;

    protected static string $resource = GtkJamAjarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
