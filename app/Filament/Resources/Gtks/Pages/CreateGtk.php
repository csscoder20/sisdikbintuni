<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGtk extends CreateRecord
{
    protected static bool $canCreateAnother = false;

    protected static string $resource = GtkResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
