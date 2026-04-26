<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
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

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
