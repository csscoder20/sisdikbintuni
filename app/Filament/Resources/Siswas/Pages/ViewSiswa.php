<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;


class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            EditAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
