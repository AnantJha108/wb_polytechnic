<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    protected $fillable = ['name', 'status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_user_map', 'master_id', 'menu_id');
    }
}
