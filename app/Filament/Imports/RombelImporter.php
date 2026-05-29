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

    /**
     * Melacak kombinasi nama+tingkat yang sudah diproses dalam satu sesi impor.
     * Di-reset setiap kali impor baru dimulai via resetDuplicateTracker().
     */
    private static array $seenKeys = [];

    /**
     * Dipanggil oleh processImport() sebelum iterasi baris dimulai,
     * agar tracker bersih untuk setiap sesi impor.
     */
    public static function resetDuplicateTracker(): void
    {
        self::$seenKeys = [];
    }

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->label('Nama Rombel')
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

    public function resolveRecord(): ?Rombel
    {
        $nama = trim((string) ($this->data['nama'] ?? ''));

        // Lewati baris contoh / instruksi
        if (
            str_contains(strtolower($nama), 'diisi dengan') ||
            $nama === 'X-IPA-1'
        ) {
            return null;
        }

        $sekolahId = $this->options['sekolah_id'] ?? (filament()->getTenant()?->id ?? $this->import->user->sekolah?->id);

        if (! $sekolahId) {
            throw new \Exception(
                'Gagal mendeteksi data Sekolah. Pastikan Anda melakukan import di dalam panel sekolah yang benar.'
            );
        }

        $tingkat = (int) ($this->data['tingkat'] ?? 0);

        // ── Cek duplikat dalam file ──────────────────────────────────────────
        $key = strtolower($nama) . '||' . $tingkat . '||' . $sekolahId;

        if (isset(self::$seenKeys[$key])) {
            throw new \Exception(
                "Duplikat dalam file: Rombel \"{$nama}\" tingkat {$tingkat} muncul lebih dari sekali."
            );
        }

        self::$seenKeys[$key] = true;

        // ── Cek duplikat di database ─────────────────────────────────────────
        $exists = Rombel::where('sekolah_id', $sekolahId)
            ->where('nama', $nama)
            ->where('tingkat', $tingkat)
            ->exists();

        if ($exists) {
            throw new \Exception(
                "Sudah ada: Rombel \"{$nama}\" tingkat {$tingkat} sudah terdaftar di sistem."
            );
        }

        // Kembalikan instance baru (bukan firstOrNew) karena sudah lolos pengecekan di atas
        $rombel = new Rombel();
        $rombel->sekolah_id = $sekolahId;

        return $rombel;
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
