<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkKeuanganImporter;
use Filament\Resources\Pages\ListRecords;
class ListGtkKeuangan extends ListRecords {
    protected static string $resource = GtkKeuanganResource::class;
    protected function getHeaderActions(): array { 
        return [
            ImportAction::make()
                ->importer(GtkKeuanganImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data Rekening & NPWP')
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
                        ['url' => route('import-template.download', ['importer' => 'GtkKeuangan'])]
                    )
                ))
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Rekening & NPWP')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false)
        ]; 
    }
}
