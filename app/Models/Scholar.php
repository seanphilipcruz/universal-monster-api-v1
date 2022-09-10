<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholar extends Model
{
    protected $fillable = [
        'student_id',
        'batch_id',
        'scholar_type',
    ];

    public function Student() {
        return $this->belongsTo(Student::class);
    }

    public function Batch() {
        return $this->belongsTo(Batch::class);
    }
}
