<?php

namespace App\Filament\Resources\Mapels\Pages;

use App\Filament\Resources\Mapels\MapelResource;
use Filament\Actions\CreateAction;
use App\Filament\Actions\ExcelImportAction;
use App\Filament\Imports\MapelImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;

class ListMapels extends ListRecords
{
    use HasImportTemplate;

    protected static string $resource = MapelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Mapel')
                ->modalHeading('Tambah Mata Pelajaran')
                ->modalSubmitActionLabel('Simpan Data')
                ->createAnother(false),
        ];
    }
}
