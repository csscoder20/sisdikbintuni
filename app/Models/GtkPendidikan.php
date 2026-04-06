<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtkPendidikan extends Model
{
    protected $table = 'tbl_gtk_pendidikan';

    protected $fillable = [
        'jenis_gtk',
        'slta',
        'di',
        'dii',
        'diii',
        's1',
        's2',
        's3',
    ];

    protected $casts = [
        'slta' => 'integer',
        'di' => 'integer',
        'dii' => 'integer',
        'diii' => 'integer',
        's1' => 'integer',
        's2' => 'integer',
        's3' => 'integer',
    ];
}
