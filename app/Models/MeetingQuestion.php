<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingQuestion extends Model
{
    protected $fillable = [
        'meeting_id',
        'user_id',
        'user_name',
        'isi',
        'parent_id'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)
            ->withDefault(['name' => $this->user_name ?? 'Tidak diketahui']);
    }


    public function replies()
    {
        return $this->hasMany(MeetingQuestion::class, 'parent_id');
    }


    // ── Accessor ──

    /**
     * Nama penanya yang aman ditampilkan di UI.
     * Cara pakai di blade: {{ $question->display_user_name }}
     */
    public function getDisplayUserNameAttribute(): string
    {
        if ($this->user_id !== null) {
            return optional($this->user)->name
                ?? $this->user_name
                ?? 'Tidak diketahui';
        }

        return $this->user_name ?? 'Tidak diketahui';
    }

    /**
     * Inisial 2 huruf untuk avatar pertanyaan.
     */
    public function getUserInitialsAttribute(): string
    {
        return strtoupper(substr($this->display_user_name, 0, 2));
    }
}
