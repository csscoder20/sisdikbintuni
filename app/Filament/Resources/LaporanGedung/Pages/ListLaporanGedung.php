<?php

namespace App\Filament\Resources\LaporanGedung\Pages;

use App\Filament\Resources\LaporanGedung\LaporanGedungResource;
use App\Filament\Actions\ValidateChecklistAction;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use App\Filament\Imports\LaporanGedungImporter;
use Filament\Resources\Pages\ListRecords;

class ListLaporanGedung extends ListRecords
{
    protected static string $resource = LaporanGedungResource::class;

    public function getLaporanStatus(string $type): bool
    {
        $sekolahId = filament()->getTenant()?->id;
        $laporan = \App\Models\Laporan::where([
            'sekolah_id' => $sekolahId,
            'bulan' => (int) date('m'),
            'tahun' => (int) date('Y'),
        ])->first();

        $column = "is_{$type}_valid";
        return $laporan ? (bool) $laporan->$column : false;
    }

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(LaporanGedungImporter::class)
                ->label('Import Data')
                ->modalHeading('Import Data Keadaan Gedung')
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
                        ['url' => route('import-template.download', ['importer' => 'LaporanGedung'])]
                    )
                ))
                ->modalSubmitActionLabel('Import Sekarang')
                ->color('info'),
            CreateAction::make()
                ->label('Tambah Data')
                ->modalHeading('Tambah Laporan Gedung')
                ->modalSubmitActionLabel('Tambah Data')
                ->createAnother(false),
            ValidateChecklistAction::make('validateKondisiSarpras', 'kondisi_sarpras', fn() => \App\Models\LaporanGedung::whereHas('laporan', fn($q) => $q->where('sekolah_id', filament()->getTenant()?->id))->exists()),
        ];
    }
}
