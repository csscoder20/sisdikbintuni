<?php

namespace App\Filament\Resources\LaporanGedung\Pages;

use App\Filament\Resources\LaporanGedung\LaporanGedungResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;


class EditLaporanGedung extends EditRecord
{
    protected static string $resource = LaporanGedungResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
