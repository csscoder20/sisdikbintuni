<?php

namespace App\Filament\Imports;

use App\Models\GtkPendidikan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class GtkPendidikanImporter extends Importer
{
    protected static ?string $model = GtkPendidikan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nik_gtk')
                ->requiredMapping()
                ->label('NIK GTK'),
            ImportColumn::make('gelar_akademik'),
            ImportColumn::make('thn_tamat_sd'),
            ImportColumn::make('thn_tamat_smp'),
            ImportColumn::make('thn_tamat_sma'),
            ImportColumn::make('thn_tamat_s1'),
            ImportColumn::make('jurusan_s1'),
            ImportColumn::make('perguruan_tinggi_s1'),
        ];
    }

    public function resolveRecord(): ?GtkPendidikan
    {
        $gtk = Gtk::where('nik', $this->data['nik_gtk'])->first();
        
        if (!$gtk) {
            return null;
        }

        return GtkPendidikan::firstOrNew([
            'gtk_id' => $gtk->id,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your gtk pendidikan import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
