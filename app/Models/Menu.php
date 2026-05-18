<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['name', 'slug', 'menu_id'];

    public function children()
    {
        return $this->hasMany(Menu::class, 'menu_id');
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'menu_user_maps');
    // }
}
