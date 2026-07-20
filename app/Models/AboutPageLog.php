<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPageLog extends Model
{
    protected $fillable = [
        'about_page_id',
        'action',
        'reason',
        'performed_by',
        'ip_address',
    ];

    public function performer()
    {
        return $this->belongsTo(\App\Models\User::class, 'performed_by');
    }

    public function getActorNameAttribute()
    {
        $user = $this->performedByUser;
        if (!$user) return 'System';

        if ($user->master && $user->master->name === 'operator') {
            return $user->username;
        }

        return $user->college->name ?? $user->username;
    }
}
