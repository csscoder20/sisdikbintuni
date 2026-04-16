<?php

namespace App\Filament\Resources\Mapels\Pages;

use App\Filament\Resources\Mapels\MapelResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\MapelImporter;
use Filament\Resources\Pages\ListRecords;

class ListMapels extends ListRecords
{
    protected static string $resource = MapelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(MapelImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data Mapel')
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Data Mapel')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
        ];
    }
}
