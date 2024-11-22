<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Meeting extends Model
{
    protected $fillable = ['title', 'url', 'start_time', 'end_time', 'description', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function attendees()
    {
        return $this->hasMany(MeetingAttendee::class);
    }
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }
    public static function scopeForCurrentUser($query)
    {
        // Get all meetings where the user is the owner (user_id is the logged-in user's id)
        // or the user is in the attendees list
        return $query->where('user_id', Auth::id())
            ->orWhereHas('attendees', function ($query) {
                $query->where('user_id', Auth::id());
            });
    }
    public function canAccess($user)
    {
        $userId = $user instanceof User ? $user->id : $user;

        // Check if the user is the owner
        if ($this->user_id === $userId) {
            return true;
        }

        // Check if the user is an attendee
        return $this->attendees()->where('user_id', $userId)->exists();
    }
}
