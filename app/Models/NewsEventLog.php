<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsEventLog extends Model
{
    protected $fillable = [
        'news_event_id',
        'action',
        'reason',
        'performed_by',
        'ip_address',
    ];

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}