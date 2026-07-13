<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegePage extends Model
{
    protected $table = 'college_pages';

    protected $fillable = [
        'college_id', 'page', 'description', 'banner', 'principle_image',
        'principle_message', 'status', 'reject_reason', 'revert_reason',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function logs()
    {
        return $this->hasMany(CollegePageLog::class);
    }
}