<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gimmick extends Model
{
    protected $table = 'gimikboards';

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'description',
        'sub_description',
        'school_id',
        'image',
        'location',
        'is_published'
    ];

    public function School() {
        return $this->belongsTo(School::class);
    }
}
