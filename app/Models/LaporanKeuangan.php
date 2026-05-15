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
        'tanggal',
        'jenis_transaksi',
        'keterangan',
        'nominal',
        'saldo',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'saldo' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $laporanKeuangan): void {
            $laporanKeuangan->nominal = self::parseNominal($laporanKeuangan->nominal);
            $laporanKeuangan->saldo = $laporanKeuangan->jenis_transaksi === 'kredit'
                ? $laporanKeuangan->nominal
                : -$laporanKeuangan->nominal;
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
