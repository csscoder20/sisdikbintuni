<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GtkDaerah extends Model
{
    protected $table = 'tbl_gtk_daerah';

    protected $fillable = [
        'jenis_gtk',
        'papua_l',
        'papua_p',
        'papua_jml',
        'non_papua_l',
        'non_papua_p',
        'non_papua_jml',
    ];

    protected $casts = [
        'papua_l' => 'integer',
        'papua_p' => 'integer',
        'papua_jml' => 'integer',
        'non_papua_l' => 'integer',
        'non_papua_p' => 'integer',
        'non_papua_jml' => 'integer',
    ];
}
