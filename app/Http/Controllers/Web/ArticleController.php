<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function articles(Request $request) {
        $data = app('App\Http\Controllers\Website\ArticleController')->index($request)->getData();

        return view('web.articles.index', compact('data'));
    }

    public function showArticle($article_uid) {
        $data = app('App\Http\Controllers\Website\ArticleController')->show($article_uid)->getData();

        return view('web.articles.view', compact('data'));
    }
}
