<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GtkTugasTambahan extends Model
{
    use SoftDeletes;
    protected $table = 'gtk_tugas_tambahan';
    protected $fillable = [
        'gtk_id',
        'tugas_tambahan',
        'jumlah_jam',
    ];

    public function gtk()
    {
        return $this->belongsTo(Gtk::class);
    }
}
