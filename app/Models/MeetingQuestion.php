<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingQuestion extends Model
{
    protected $fillable = [
        'meeting_id',
        'user_id',
        'isi',
        'parent_id'
    ];

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
