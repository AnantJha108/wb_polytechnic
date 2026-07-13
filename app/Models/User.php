<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'college_id',
        'username',
        'phone',
        'master_id',
        'email',
        'password',
        'otp',
        'role',
        'otp_expires_at',
        'login_attempts',
        'locked_until',
        'otp_attempts',
        'otp_resend_locked_until',
        'otp_lock_started_at',
        'lock_started_at',
    ];


    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function master()
    {
        return $this->belongsTo(Master::class, 'master_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_user_maps', 'user_id', 'menu_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
