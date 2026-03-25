<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackMessage extends Model
{
    protected $fillable = [
        'feedback_id',
        'sender',
        'message'
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
}
