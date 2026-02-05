<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'title',
        'date',
        'time',
        'location',
        'participants',
        'agenda',
        'discussion',
        'status',
        'created_by'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
