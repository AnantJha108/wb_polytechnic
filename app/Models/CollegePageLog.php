<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollegePageLog extends Model
{
    protected $fillable = ['college_page_id', 'action', 'reason', 'performed_by', 'ip_address'];

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function page()
    {
        return $this->belongsTo(CollegePage::class, 'college_page_id');
    }
}