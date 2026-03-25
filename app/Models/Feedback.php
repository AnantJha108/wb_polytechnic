<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'college_id',
        'name',
        'email',
        'message',
        'ack_number',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function messages()
    {
        return $this->hasMany(FeedbackMessage::class);
    }

    
}
