<?php

namespace App\Filament\Imports;

use App\Models\GtkKeuangan;
use App\Models\Gtk;
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
                ->label('NIK GTK')
                ->example('3201010101010005'),
            ImportColumn::make('nomor_rekening')
                ->rules(['string', 'max:255'])
                ->example('1234567890'),
            ImportColumn::make('nama_bank')
                ->rules(['string', 'max:255'])
                ->example('Bank Papua'),
            ImportColumn::make('npwp')
                ->label('NPWP')
                ->rules(['string', 'max:255'])
                ->example('12.345.678.9-123.000'),
        ];
    }

    public function resolveRecord(): ?GtkKeuangan
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        $gtk = Gtk::where('nik', $this->data['nik_gtk'])
            ->where('sekolah_id', $sekolahId)
            ->first();
        
        if (!$gtk) {
            return null;
        }

        return GtkKeuangan::firstOrNew([
            'gtk_id' => $gtk->id,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor data keuangan GTK selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
