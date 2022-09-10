<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tally extends Model
{
    protected $table = 'tallies';

    protected $fillable = [
        'chart_id',
        'result',
        'last_result',
        'dated'
    ];

    public function Chart() {
        return $this->belongsTo(Chart::class);
    }
}
