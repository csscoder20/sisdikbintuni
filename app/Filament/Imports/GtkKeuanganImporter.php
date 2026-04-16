<?php

namespace App\Filament\Imports;

use App\Models\GtkKeuangan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class GtkKeuanganImporter extends Importer
{
    protected static ?string $model = GtkKeuangan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nik_gtk')
                ->requiredMapping()
                ->label('NIK GTK'),
            ImportColumn::make('nomor_rekening')
                ->rules(['string', 'max:255']),
            ImportColumn::make('nama_bank')
                ->rules(['string', 'max:255']),
            ImportColumn::make('npwp')
                ->label('NPWP')
                ->rules(['string', 'max:255']),
        ];
    }

    public function resolveRecord(): ?GtkKeuangan
    {
        $gtk = Gtk::where('nik', $this->data['nik_gtk'])->first();
        
        if (!$gtk) {
            return null;
        }

        return GtkKeuangan::firstOrNew([
            'gtk_id' => $gtk->id,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your gtk keuangan import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
