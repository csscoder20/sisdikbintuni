<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtkStatusKepegawaian extends Model
{
    protected $table = 'tbl_gtk_status_kepegawaian';

    protected $fillable = [
        'jenis_gtk',
        'pns',
        'pppk',
        'honorer_sekolah',
    ];

    protected $casts = [
        'pns' => 'integer',
        'pppk' => 'integer',
        'honorer_sekolah' => 'integer',
    ];
}
