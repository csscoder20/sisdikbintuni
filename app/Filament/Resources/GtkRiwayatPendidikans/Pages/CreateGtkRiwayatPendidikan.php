<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Pages;

use App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGtkRiwayatPendidikan extends CreateRecord
{
    protected static bool $canCreateAnother = false;

    protected static string $resource = GtkRiwayatPendidikanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
