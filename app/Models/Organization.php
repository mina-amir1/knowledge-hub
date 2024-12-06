<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'about',
        'no_employees',
        'facebook_link',
        'instagram_link',
        'website_link',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'admin_id',
        'logo'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function expertises()
    {
        return $this->belongsToMany(Expertise::class);
    }

    public function canAccess($user)
    {
        $userId = $user instanceof User ? $user->id : $user;

        return $this->admin_id === $userId || auth()->user()->super_admin;
    }
}
