<?php
namespace App\Filament\Resources\KehadiranGtk\Pages;
use App\Filament\Resources\KehadiranGtk\KehadiranGtkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListKehadiranGtk extends ListRecords {
    protected static string $resource = KehadiranGtkResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
