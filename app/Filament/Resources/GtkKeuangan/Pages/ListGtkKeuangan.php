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
                ->label('Impor Data Rekening dan NPWP')
                ->modalHeading('Impor Data Rekening dan NPWP')
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
                ->modalSubmitActionLabel('Impor Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data Rekening')
                ->modalHeading('Tambah Data Rekening dan NPWP')
                ->modalSubmitActionLabel('Simpan Data Rekening')
                ->createAnother(false)
        ]; 
    }
}
