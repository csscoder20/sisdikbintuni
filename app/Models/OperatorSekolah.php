<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorSekolah extends Model
{
    protected $table = 'operator_sekolah';
            protected $fillable = [
                'user_id',
                'sekolah_id',
            ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }
}