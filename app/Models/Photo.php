<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'jock_id',
        'article_id',
        'show_id',
        'batch_id',
        'file',
        'name',
    ];

    public function Show() {
        return $this->belongsTo(Show::class);
    }

    public function Jock() {
        return $this->belongsTo(Jock::class);
    }

    public function Article() {
        return $this->belongsTo(Article::class);
    }

    public function Batch() {
        return $this->belongsTo(Batch::class);
    }
}
