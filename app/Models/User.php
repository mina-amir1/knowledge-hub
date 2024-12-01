<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'activation_token',
        'is_blocked',
        'phone',
        'organisation_name',
        'organisation_about',
        'no_employees',
        'social_media',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function isActive(): bool
    {
        return $this->hasVerifiedEmail() && !$this->is_blocked;
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_blocked', false)->whereNotNull('email_verified_at');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function expertises()
    {
        return $this->belongsToMany(Expertise::class);
    }
}
