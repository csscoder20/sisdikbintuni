<?php

namespace App\Filament\Imports;

use App\Models\Mapel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class MapelImporter extends Importer
{
    protected static ?string $model = Mapel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_mapel')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('nama_mapel')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('jjp')
                ->numeric()
                ->rules(['integer']),
            ImportColumn::make('jenjang')
                ->rules(['string', 'max:255']),
        ];
    }

    public function resolveRecord(): Mapel
    {
        return Mapel::firstOrNew([
            'kode_mapel' => $this->data['kode_mapel'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your mapel import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
