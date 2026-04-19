<?php

namespace App\Filament\Imports;

use App\Models\Mapel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Filament\Facades\Filament;

class MapelImporter extends Importer
{
    protected static ?string $model = Mapel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_mapel')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('MTK-01'),
            ImportColumn::make('nama_mapel')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Matematika'),
            ImportColumn::make('jjp')
                ->numeric()
                ->rules(['integer'])
                ->example('2'),
            ImportColumn::make('jenjang')
                ->rules(['string', 'max:255'])
                ->example('SMA'),
        ];
    }

    public function resolveRecord(): Mapel
    {
        $panel = Filament::getCurrentPanel();
        $panelId = $panel ? $panel->getId() : null;
        $tenantJenjang = Filament::getTenant()?->jenjang ?: (in_array($panelId, ['sma', 'smk']) ? strtoupper($panelId) : null);
        $jenjang = $tenantJenjang ?? ($this->data['jenjang'] ?? null);

        $record = Mapel::firstOrNew([
            'kode_mapel' => $this->data['kode_mapel'],
            'jenjang' => $jenjang,
        ]);

        // Pastikan jenjang sesuai dengan tenant jika sedang dalam konteks sekolah
        if ($tenantJenjang) {
            $record->jenjang = $tenantJenjang;
        }

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor mata pelajaran selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
