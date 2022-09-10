<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'name',
        'remarks'
    ];

    public function Batch() {
        return $this->belongsToMany(Batch::class);
    }
}
