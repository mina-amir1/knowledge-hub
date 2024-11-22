<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body', 'user_id', 'thread_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    public function hasApprovedFiles()
    {
        return $this->attachments()->where('status', Attachment::APPROVED)->exists();
    }
}
