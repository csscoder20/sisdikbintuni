<?php

namespace App\Filament\Resources\GtkRiwayatPendidikans\Pages;

use App\Filament\Resources\GtkRiwayatPendidikans\GtkRiwayatPendidikanResource;
use Filament\Resources\Pages\EditRecord;

class EditGtkRiwayatPendidikan extends EditRecord
{
    protected static string $resource = GtkRiwayatPendidikanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
