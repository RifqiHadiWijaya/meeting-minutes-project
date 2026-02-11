<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingQuestion extends Model
{
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(MeetingQuestion::class, 'parent_id');
    }
}
