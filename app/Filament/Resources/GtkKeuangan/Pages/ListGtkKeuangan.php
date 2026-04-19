<?php
namespace App\Filament\Resources\GtkKeuangan\Pages;
use App\Filament\Resources\GtkKeuangan\GtkKeuanganResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\GtkKeuanganImporter;
use App\Filament\Traits\HasImportTemplate;
use Filament\Resources\Pages\ListRecords;
class ListGtkKeuangan extends ListRecords {
    use HasImportTemplate;

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
                        <div class="text-sm">
                            <x-filament::link wire:click="mountAction('downloadExample')" tag="button" type="button" color="success" class="font-bold hover:underline">
                                Unduh contoh berkas (.csv)
                            </x-filament::link>
                        </div>
                        BLADE
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
