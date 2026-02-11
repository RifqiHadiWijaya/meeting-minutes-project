<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'judul', 'tanggal', 'waktu', 'lokasi',
        'jenis', 'topik', 'partisipan',
        'notulensi', 'status',
        'created_by', 'notulen'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notulis()
    {
        return $this->belongsTo(User::class, 'notulen');
    }

    public function questions()
    {
        return $this->hasMany(MeetingQuestion::class);
    }
}
