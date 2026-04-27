<?php
namespace App\Filament\Resources\Kelulusan\Pages;
use App\Filament\Resources\Kelulusan\KelulusanResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

class EditKelulusan extends EditRecord {
    protected static string $resource = KelulusanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array { return [
            RestoreAction::make(),
            ForceDeleteAction::make(),]; }
}
