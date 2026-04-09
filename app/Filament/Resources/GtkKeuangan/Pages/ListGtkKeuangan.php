<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListGtkKeuangan extends ListRecords {
    protected static string $resource = GtkKeuanganResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
