<?php
namespace App\Filament\Resources\Kelulusan\Pages;
use App\Filament\Resources\Kelulusan\KelulusanResource;
use Filament\Resources\Pages\CreateRecord;
class CreateKelulusan extends CreateRecord {
    protected static bool $canCreateAnother = false;

    protected static string $resource = KelulusanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
