<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Resources\Pages\CreateRecord;
class CreateGtkKeuangan extends CreateRecord {
    protected static bool $canCreateAnother = false;

    protected static string $resource = GtkKeuanganResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
