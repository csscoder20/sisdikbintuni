<?php

namespace App\Filament\Imports;

use App\Models\Gtk;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class GtkImporter extends Importer
{
    protected static ?string $model = Gtk::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('nip')
                ->rules(['string', 'max:255']),
            ImportColumn::make('nik')
                ->rules(['string', 'max:255']),
            ImportColumn::make('nuptk')
                ->rules(['string', 'max:255']),
            ImportColumn::make('jenis_kelamin')
                ->rules(['string', 'max:255']),
            ImportColumn::make('status_kepegawaian')
                ->rules(['string', 'max:255']),
        ];
    }

    public function resolveRecord(): Gtk
    {
        return Gtk::firstOrNew([
            'sekolah_id' => filament()->getTenant()->id,
            'nik' => $this->data['nik'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your gtk import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
