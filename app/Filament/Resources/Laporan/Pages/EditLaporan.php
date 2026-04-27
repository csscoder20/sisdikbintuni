<?php
namespace App\Filament\Resources\Laporan\Pages;
use App\Filament\Resources\Laporan\LaporanResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

class EditLaporan extends EditRecord {
    protected static string $resource = LaporanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array { return [
            RestoreAction::make(),
            ForceDeleteAction::make(),]; }
}
