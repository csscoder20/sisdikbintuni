<?php

namespace App\Filament\Imports;

use App\Models\Rombel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class RombelImporter extends Importer
{
    protected static ?string $model = Rombel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('X-IPA-1'),
            ImportColumn::make('tingkat')
                ->label('Tingkat')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'in:10,11,12'])
                ->example('10'),
        ];
    }

    public function resolveRecord(): Rombel
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (! $sekolahId) {
            throw new \Exception('Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.');
        }

        return Rombel::firstOrNew([
            'sekolah_id' => $sekolahId,
            'nama' => $this->data['nama'],
            'tingkat' => (int) $this->data['tingkat'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor rombel selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
