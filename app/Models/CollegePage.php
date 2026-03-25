<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegePage extends Model
{
    protected $fillable = [
        'college_id',
        'page',
        'description'
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
