<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    protected $fillable = [
        'show_id',
        'jock_id',
        'day',
        'start',
        'end',
        'location'
    ];

    public function Show() {
        return $this->belongsTo(Show::class);
    }

    public function Jock() {
        return $this->belongsToMany(Jock::class);
    }
}
