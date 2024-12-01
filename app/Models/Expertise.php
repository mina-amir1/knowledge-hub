<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    protected $fillable = ['field'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
