<?php

namespace App\Filament\Resources\LaporanGedung\Pages;

use App\Filament\Resources\LaporanGedung\LaporanGedungResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanGedung extends CreateRecord
{
    protected static bool $canCreateAnother = false;

    protected static string $resource = LaporanGedungResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
