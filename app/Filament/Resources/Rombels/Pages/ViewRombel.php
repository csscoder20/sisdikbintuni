<?php

namespace App\Filament\Resources\Rombels\Pages;

use App\Filament\Resources\Rombels\RombelResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;


class ViewRombel extends ViewRecord
{
    protected static string $resource = RombelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            RestoreAction::make(),
            EditAction::make(),
            ForceDeleteAction::make(),
        ];
    }
}
