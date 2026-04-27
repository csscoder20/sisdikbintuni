<?php

namespace App\Filament\Resources\Mapels\Pages;

use App\Filament\Resources\Mapels\MapelResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;


class EditMapel extends EditRecord
{
    protected static string $resource = MapelResource::class;

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
