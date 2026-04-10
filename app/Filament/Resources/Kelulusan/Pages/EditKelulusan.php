<?php
namespace App\Filament\Resources\Kelulusan\Pages;
use App\Filament\Resources\Kelulusan\KelulusanResource;
use Filament\Resources\Pages\EditRecord;
class EditKelulusan extends EditRecord {
    protected static string $resource = KelulusanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array { return []; }
}
