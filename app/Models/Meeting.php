<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $fillable = [
        'judul', 'tanggal', 'waktu', 'lokasi',
        'jenis', 'topik', 'partisipan',
        'notulensi', 'status',
        'created_by', 'creator_name',
        'notulen', 'notulen_name',
    ];

    // Relasi

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')
            ->withDefault(['name' => $this->creator_name ?? 'Tidak diketahui']);
    }

    public function notulis()
    {
        return $this->belongsTo(User::class, 'notulen')
            ->withDefault(['name' => $this->notulen_name ?? 'Tidak diketahui']);
    }

    public function questions()
    {
        return $this->hasMany(MeetingQuestion::class)
            ->whereNull('parent_id');
    }

    // ── Accessors ──

    /**
     * Nama pembuat yang aman ditampilkan di UI.
     *
     * Prioritas:
     *   1. Nama dari relasi (user masih ada di database)
     *   2. Snapshot creator_name (user sudah dihapus)
     *   3. Fallback "Tidak diketahui"
     *
     * Cara pakai di blade: {{ $meeting->display_creator_name }}
     */
    public function getDisplayCreatorNameAttribute(): string
    {
        // Jika user masih ada, pakai nama dari relasi
        if ($this->created_by !== null) {
            return optional($this->creator)->name
                ?? $this->creator_name
                ?? 'Tidak diketahui';
        }

        // User sudah dihapus (created_by = NULL), pakai snapshot
        return $this->creator_name ?? 'Tidak diketahui';
    }


    /**
     * Nama notulis yang aman ditampilkan di UI.
     * Cara pakai di blade: {{ $meeting->display_notulen_name }}
     */
    public function getDisplayNotulenNameAttribute(): string
    {
        if ($this->notulen !== null) {
            return optional($this->notulis)->name
                ?? $this->notulen_name
                ?? 'Tidak diketahui';
        }

        return $this->notulen_name ?? 'Tidak diketahui';
    }


    /**
     * Inisial 2 huruf untuk avatar.
     * Cara pakai di blade: {{ $meeting->creator_initials }}
     */
    public function getCreatorInitialsAttribute(): string
    {
        return strtoupper(substr($this->display_creator_name, 0, 2));
    }
}
