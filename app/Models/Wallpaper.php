<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallpaper extends Model
{
    protected $fillable = [
        'name',
        'image',
        'location',
        'device',
    ];
}
