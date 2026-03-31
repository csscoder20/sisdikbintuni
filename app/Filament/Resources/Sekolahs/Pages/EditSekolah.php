<?php

namespace App\Filament\Resources\Sekolahs\Pages;

use App\Filament\Resources\Sekolahs\SekolahResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSekolah extends EditRecord
{
    protected static string $resource = SekolahResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()->role === 'operator') {
            return [];
        }

        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        if (auth()->user()->role === 'operator') {
            return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
        }

        return $this->getResource()::getUrl('index');
    }
}
