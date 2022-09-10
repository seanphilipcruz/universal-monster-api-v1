<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    protected $fillable = [
        'number',
        'image',
        'title',
        'sub_title',
        'link',
        'location'
    ];
}
