<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanKeuangan extends Model
{
    use SoftDeletes;
    use \App\Traits\HasActivityLog;

    protected $table = 'laporan_keuangan';

    protected $fillable = [
        'laporan_id',
        'sumber_dana',
        'penerimaan',
        'pengeluaran',
        'saldo',
        'keterangan',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $laporanKeuangan): void {
            $laporanKeuangan->penerimaan = self::parseNominal($laporanKeuangan->penerimaan);
            $laporanKeuangan->pengeluaran = self::parseNominal($laporanKeuangan->pengeluaran);
            $laporanKeuangan->saldo = $laporanKeuangan->penerimaan - $laporanKeuangan->pengeluaran;
        });
    }

    private static function parseNominal(mixed $value): float
    {
        if (blank($value)) {
            return 0;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);

        if (str_contains($value, ',')) {
            return (float) str_replace(',', '.', str_replace('.', '', $value));
        }

        if (preg_match('/^-?\d+\.\d{1,2}$/', $value) === 1) {
            return (float) $value;
        }

        return (float) str_replace('.', '', $value);
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
