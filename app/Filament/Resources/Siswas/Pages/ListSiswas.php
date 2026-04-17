<?php

namespace App\Filament\Resources\Siswas\Pages;

use App\Filament\Resources\Siswas\SiswaResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\SiswaImporter;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(SiswaImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data Siswa')
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
                        ['url' => route('import-template.download', ['importer' => 'Siswa'])]
                    )
                ))
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Data Siswa')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
        ];
    }
}
