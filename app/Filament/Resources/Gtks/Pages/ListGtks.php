<?php

namespace App\Filament\Resources\Gtks\Pages;

use App\Filament\Resources\Gtks\GtkResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkImporter;
use Filament\Resources\Pages\ListRecords;

class ListGtks extends ListRecords
{
    protected static string $resource = GtkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(GtkImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data GTK')
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
                        ['url' => route('import-template.download', ['importer' => 'Gtk'])]
                    )
                ))
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Data GTK')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
        ];
    }
}
