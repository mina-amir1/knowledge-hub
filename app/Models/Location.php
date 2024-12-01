<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['province'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
