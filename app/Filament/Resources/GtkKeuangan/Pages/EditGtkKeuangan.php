<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

class EditGtkKeuangan extends EditRecord {
    protected static string $resource = GtkKeuanganResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array { return [
            RestoreAction::make(),
            ForceDeleteAction::make(),]; }
}
