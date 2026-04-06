<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingDokumentasi extends Model
{
    protected $table = 'meeting_dokumentasi';

    protected $fillable = [
        'meeting_id',
        'nama_file',
        'path_file',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}