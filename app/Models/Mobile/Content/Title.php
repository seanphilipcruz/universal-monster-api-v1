<?php

namespace App\Models\Mobile\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $table = 'mobile_app_titles';

    protected $fillable = [
        'chart_title',
        'chart_sub_title',
        'article_title',
        'article_sub_title',
        'podcast_title',
        'podcast_sub_title',
        'articles_main_page_title',
        'podcast_main_page_title',
        'youtube_main_page_title',
        'location'
    ];

    public function Asset() {
        return $this->belongsTo(Asset::class);
    }
}
