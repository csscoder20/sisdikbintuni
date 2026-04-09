<?php
namespace App\Filament\Resources\Kelulusan\Pages;
use App\Filament\Resources\Kelulusan\KelulusanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListKelulusan extends ListRecords {
    protected static string $resource = KelulusanResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
