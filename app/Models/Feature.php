<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $table = 'featured_indiegrounds';

    protected $fillable = [
        'indieground_id',
        'content',
        'month',
        'year',
        'location'
    ];

    public function Indie() {
        return $this->belongsTo(Indie::class, 'indieground_id');
    }
}
