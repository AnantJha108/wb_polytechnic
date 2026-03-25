<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'template_name',
        'template_path',
        'status'
    ];

    public function colleges()
    {
        return $this->hasMany(College::class);
    }
}
