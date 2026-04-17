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
                ->modalDescription(fn (ImportAction $action) => new \Illuminate\Support\HtmlString(
                    \Illuminate\Support\Facades\Blade::render(
                        <<<'BLADE'
                        <x-filament::link wire:click="mountAction('downloadExample')" tag="button" type="button" color="primary">
                            {{ __('filament-actions::import.modal.actions.download_example.label') }}
                        </x-filament::link>
                        atau
                        <x-filament::link href="{{ $url }}" tag="a" target="_blank" color="primary">
                            Download Template Excel (.xlsx)
                        </x-filament::link>
                        BLADE,
                        ['url' => route('import-template.download', ['importer' => 'Rombel'])]
                    )
                ))
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
