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
            ImportColumn::make('nama')
                ->label('Nama GTK (Opsional - untuk pencarian)')
                ->rules(['string', 'max:255'])
                ->example('Siti Maimunah'),
        ];
    }

    public function resolveRecord(): ?GtkKeuangan
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        if (!$sekolahId) {
            return null;
        }

        $nik = $this->data['nik_gtk'] ?? null;
        $nama = $this->data['nama'] ?? null;
        
        // Search for GTK by various identifiers
        $gtkQuery = Gtk::where('sekolah_id', $sekolahId);

        $gtk = null;

        // 1. Try matching by NIK if provided
        if ($nik && !blank($nik)) {
            $gtk = (clone $gtkQuery)->where('nik', $nik)->first();
            
            // 2. Fallback to matching by NIP if NIK didn't match
            if (!$gtk) {
                $gtk = (clone $gtkQuery)->where('nip', $nik)->first();
            }
        }

        // 3. Fallback to matching by Nama if NIK/NIP failed and Nama is provided
        if (!$gtk && $nama && !blank($nama)) {
            $gtk = (clone $gtkQuery)->where('nama', 'like', "%{$nama}%")->first();
        }

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

        if ($skippedRowsCount = $import->getSkippedRowsCount()) {
            $body .= ' ' . Number::format($skippedRowsCount) . ' baris dilewati (Data GTK tidak ditemukan).';
        }

        return $body;
    }

}
