<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaDaerah extends Model
{
    use HasFactory;

    protected $table = 'siswa_daerah';

    protected $fillable = [
        'id_rombel',
        'nama_rombel',
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

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
    }
}
