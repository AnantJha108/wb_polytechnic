<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsEvent extends Model
{
    protected $table = 'news_events_notice_announcement';

    protected $fillable = [
        'college_id',
        'title',
        'type',
        'description',
        'status',
        'reject_reason',
        'revert_reason',
    ];

    public function files()
    {
        return $this->hasMany(NewsEventFile::class, 'news_event_id');
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}