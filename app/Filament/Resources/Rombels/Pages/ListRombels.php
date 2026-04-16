<?php

namespace App\Filament\Resources\Rombels\Pages;

use App\Filament\Resources\Rombels\RombelResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\RombelImporter;
use Filament\Resources\Pages\ListRecords;

class ListRombels extends ListRecords
{
    protected static string $resource = RombelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(RombelImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data Rombel')
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Data Rombel')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
        ];
    }
}
