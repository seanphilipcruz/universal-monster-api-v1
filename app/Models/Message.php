<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'topic',
        'content',
        'is_seen',
        'location'
    ];
}
