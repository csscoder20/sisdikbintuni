<?php

namespace App\Filament\Imports;

use App\Models\GtkPendidikan;
use App\Models\Gtk;
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
                ->label('NIK GTK')
                ->example('3201010101010005'),
            ImportColumn::make('gelar_belakang')
                ->example('S.Pd'),
            ImportColumn::make('thn_tamat_sd')
                ->example('1997'),
            ImportColumn::make('thn_tamat_smp')
                ->example('2000'),
            ImportColumn::make('thn_tamat_sma')
                ->example('2003'),
            ImportColumn::make('thn_tamat_s1')
                ->example('2007'),
            ImportColumn::make('jurusan_s1')
                ->example('Pendidikan Matematika'),
            ImportColumn::make('perguruan_tinggi_s1')
                ->example('Universitas Cenderawasih'),
        ];
    }

    public function resolveRecord(): ?GtkPendidikan
    {
        $sekolahId = filament()->getTenant()?->id ?? $this->import->user->sekolah?->id;

        $gtk = Gtk::where('nik', $this->data['nik_gtk'])
            ->where('sekolah_id', $sekolahId)
            ->first();
        
        if (!$gtk) {
            return null;
        }

        return GtkPendidikan::firstOrNew([
            'gtk_id' => $gtk->id,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor data pendidikan GTK selesai. ' . Number::format($import->successful_rows) . ' baris berhasil diimpor.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' baris gagal diimpor.';
        }

        return $body;
    }
}
