<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_buka',
        'status',
        'judul_lulus',
        'pesan_lulus',
        'judul_tidak_lulus',
        'pesan_tidak_lulus',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_buka' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::saved(function ($announcement) {
            // Jika pengumuman ini di-publish, ubah yang lain menjadi draft
            if ($announcement->status === 'published') {
                static::where('id', '!=', $announcement->id)
                    ->where('status', 'published')
                    ->update(['status' => 'draft']);
            }
        });
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
