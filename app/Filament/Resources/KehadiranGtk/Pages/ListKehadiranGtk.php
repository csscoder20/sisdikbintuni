<?php
namespace App\Filament\Resources\KehadiranGtk\Pages;
use App\Filament\Resources\KehadiranGtk\KehadiranGtkResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListKehadiranGtk extends ListRecords {
    protected static string $resource = KehadiranGtkResource::class;

    protected string $view = 'filament.resources.kehadiran-gtk.pages.list-kehadiran-gtk';

    protected function getHeaderActions(): array {
        return [
            ValidateChecklistAction::make('validateRekapKehadiran', 'rekap_kehadiran', fn() => \App\Models\KehadiranGtk::whereHas('gtk', fn($q) => $q->where('sekolah_id', filament()->getTenant()?->id))->exists()),
        ];
    }
}
