<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = [
        'announcement_id',
        'nisn',
        'nama',
        'kelas',
        'keterangan',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
