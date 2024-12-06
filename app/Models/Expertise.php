<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    protected $fillable = ['field'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }
}
