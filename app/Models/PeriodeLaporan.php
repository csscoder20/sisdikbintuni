<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PeriodeLaporan extends Model
{
    use HasFactory;

    protected $table = 'tbl_periode_laporan';

    protected $fillable = [
        'tahun',
        'bulan'
    ];
}
