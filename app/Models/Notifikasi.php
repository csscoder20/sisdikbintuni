<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use SoftDeletes;
    protected $table = 'notifikasi';
    protected $fillable = [
        'subject',
        'content',
        'recipient_type',
        'target_ids',
        'sender_id',
    ];

    protected $casts = [
        'target_ids' => 'array',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
