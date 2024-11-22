<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    CONST PENDING = 0;
    const APPROVED = 1;
    const BLOCKED = 2;

    protected $fillable = ['user_id','thread_id','comment_id','file_name','original_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

}
