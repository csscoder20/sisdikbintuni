<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;


class EditGtk extends EditRecord
{
    protected static string $resource = GtkResource::class;

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
