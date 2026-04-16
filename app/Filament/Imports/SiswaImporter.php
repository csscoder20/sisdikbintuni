<?php

namespace App\Filament\Imports;

use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('nisn')
                ->rules(['string', 'max:255']),
            ImportColumn::make('nik')
                ->rules(['string', 'max:255']),
            ImportColumn::make('tempat_lahir')
                ->rules(['string', 'max:255']),
            ImportColumn::make('tanggal_lahir')
                ->rules(['date']),
            ImportColumn::make('jenis_kelamin')
                ->rules(['string', 'max:255']),
            ImportColumn::make('agama')
                ->rules(['string', 'max:255']),
        ];
    }

    public function resolveRecord(): Siswa
    {
        return Siswa::firstOrNew([
            'sekolah_id' => filament()->getTenant()->id,
            'nisn' => $this->data['nisn'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your siswa import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
