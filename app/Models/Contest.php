<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $table = 'giveaways';

    protected $fillable = [
        'name',
        'type',
        'description',
        'line1',
        'line2',
        'line3',
        'is_restricted',
        'image',
        'code',
        'is_active',
        'location'
    ];

    public function Contestant() {
        return $this->belongsToMany(Contestant::class);
    }
}
