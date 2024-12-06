<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['province'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }
}
