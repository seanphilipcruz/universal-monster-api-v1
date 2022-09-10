<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'country',
        'type',
        'image'
    ];

    public function Album() {
        return $this->hasMany(Album::class);
    }

    public function Indie() {
        return $this->hasMany(Indie::class);
    }
}
