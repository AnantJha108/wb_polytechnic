<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
    protected $fillable = [
        'college_id',
        'description',
        'status',
        'reject_reason',
        'revert_reason',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}