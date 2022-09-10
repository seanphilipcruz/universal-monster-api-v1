<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'album_id',
        'name',
        'track_link',
        'type',
        'is_charted',
    ];

    public function Chart() {
        return $this->hasMany(Chart::class);
    }

    public function Album() {
        return $this->belongsTo(Album::class);
    }

    public function User() {
        return $this->belongsTo(User::class);
    }
}
