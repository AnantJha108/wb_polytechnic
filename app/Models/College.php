<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'template_id',
        'district',
        'logo'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function pages()
    {
        return $this->hasMany(CollegePage::class);
    }
}
