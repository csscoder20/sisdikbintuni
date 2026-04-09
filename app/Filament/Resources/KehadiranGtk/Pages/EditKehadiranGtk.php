<?php
namespace App\Filament\Resources\KehadiranGtk\Pages;
use App\Filament\Resources\KehadiranGtk\KehadiranGtkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditKehadiranGtk extends EditRecord {
    protected static string $resource = KehadiranGtkResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
