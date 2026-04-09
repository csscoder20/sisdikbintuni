<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
class EditGtkKeuangan extends EditRecord {
    protected static string $resource = GtkKeuanganResource::class;
    protected function getHeaderActions(): array { return [DeleteAction::make()]; }
}
