<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = [
        'name',
        'description',
        'level',
    ];

    public function Employee() {
        return $this->hasMany(Employee::class);
    }
}
