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

    /**
     * Melacak kombinasi kode+jenjang yang sudah diproses dalam satu sesi impor.
     */
    private static array $seenKeys = [];

    public static function resetDuplicateTracker(): void
    {
        self::$seenKeys = [];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kode_mapel')
                ->label('Kode Mapel')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->castStateUsing(fn($state) => blank($state) ? null : trim((string) $state))
                ->example('MTK-01'),
            ImportColumn::make('nama_mapel')
                ->label('Nama Mapel')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->castStateUsing(fn($state) => blank($state) ? null : trim((string) $state))
                ->example('Matematika'),
            ImportColumn::make('jjp')
                ->label('JJP')
                ->numeric()
                ->rules(['integer'])
                ->example('2'),
            ImportColumn::make('jenjang')
                ->label('Jenjang')
                ->rules(['max:255'])
                ->castStateUsing(fn($state) => blank($state) ? null : trim((string) $state))
                ->example('SMA'),
            ImportColumn::make('tingkat')
                ->label('Tingkat')
                ->rules(['max:255'])
                ->castStateUsing(fn($state) => blank($state) ? null : trim((string) $state))
                ->example('10'),
        ];
    }

    public function resolveRecord(): ?Mapel
    {
        $kodeMapel = trim((string) ($this->data['kode_mapel'] ?? ''));

        // Lewati baris instruksi
        if (str_contains(strtolower($kodeMapel), 'kode unik')) {
            return null;
        }

        $panel = Filament::getCurrentPanel();
        $panelId = $this->options['panel_id'] ?? ($panel ? $panel->getId() : null);
        $tenantJenjang = $this->options['tenant_jenjang'] ?? (Filament::getTenant()?->jenjang ?: (in_array($panelId, ['sma', 'smk']) ? strtoupper($panelId) : null));
        $sekolahId = Filament::getTenant()?->id;
        
        if ($panelId === 'dinas' && session('dinas_selected_sekolah_id')) {
            $sekolahId = session('dinas_selected_sekolah_id');
        }

        $jenjang = trim((string) ($this->data['jenjang'] ?? ''));
        if (!$jenjang) {
            $jenjang = strtolower($tenantJenjang ?? '');
        } else {
            $jenjang = strtolower($jenjang);
        }

        if (!$jenjang) {
            throw new \Exception('Gagal mendeteksi Jenjang. Pastikan jenjang diisi di file Excel atau Anda berada di panel yang tepat.');
        }

        $tingkat = trim((string) ($this->data['tingkat'] ?? ''));

        // Pastikan fillRecord() Filament menggunakan jenjang yang sudah divalidasi dan diubah ke huruf kecil
        $this->data['jenjang'] = $jenjang;

        // ── Cek duplikat dalam file ──────────────────────────────────────────
        $key = strtolower($kodeMapel) . '||' . $jenjang . '||' . strtolower($tingkat) . '||' . $sekolahId;

        if (isset(self::$seenKeys[$key])) {
            throw new \Exception(
                "Duplikat dalam file: Mata Pelajaran dengan kode \"{$kodeMapel}\" untuk jenjang {$jenjang} tingkat {$tingkat} muncul lebih dari sekali."
            );
        }

        self::$seenKeys[$key] = true;

        // ── Cek duplikat di database ─────────────────────────────────────────
        $query = Mapel::where('kode_mapel', $kodeMapel)->where('jenjang', $jenjang);
        
        if ($sekolahId) {
            $query->where('sekolah_id', $sekolahId);
        } else {
            $query->whereNull('sekolah_id');
        }
        
        if ($tingkat) {
            $query->where('tingkat', $tingkat);
        } else {
            $query->whereNull('tingkat');
        }
        
        if ($query->exists()) {
            throw new \Exception(
                "Sudah ada: Mata Pelajaran dengan kode \"{$kodeMapel}\" untuk jenjang {$jenjang} tingkat {$tingkat} sudah terdaftar di sistem."
            );
        }

        $record = new Mapel();
        $record->kode_mapel = $kodeMapel;
        $record->jenjang = $jenjang;
        $record->tingkat = $tingkat ?: null;
        if ($sekolahId) {
            $record->sekolah_id = $sekolahId;
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
