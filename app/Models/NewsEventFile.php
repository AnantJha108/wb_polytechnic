<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsEventFile extends Model
{
    protected $fillable = [
        'news_event_id',
        'original_name',
        'mime_type',
        'encrypted_path',
    ];

    public function newsEvent()
    {
        return $this->belongsTo(NewsEvent::class);
    }
}