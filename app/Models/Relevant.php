<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relevant extends Model
{
    protected $table = 'relateds';

    protected $fillable = [
        'article_id',
        'related_article_id'
    ];

    public function Article() {
        return $this->belongsTo(Article::class, 'related_article_id');
    }
}
