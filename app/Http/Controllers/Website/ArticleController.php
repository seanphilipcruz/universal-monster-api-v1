<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Relevant;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;

    public function index(Request $request)
    {
        // if it has no query then load the latest data.
        $articles = Article::with('Employee', 'Category', 'Content', 'Related.Article')
            ->whereNull('deleted_at')
            ->whereNotNull('published_at')
            ->where('location', $this->getStationCode())
            ->orderBy('updated_at', 'desc')
            ->simplePaginate(16);

        if($request['sidebar']) {
            $articles = Article::with('Category')
                ->whereNull('deleted_at')
                ->whereNotNull('published_at')
                ->where('location', $this->getStationCode())
                ->orderBy('updated_at', 'desc')
                ->get()
                ->take(5);

            foreach ($articles as $article) {
                $article['published_at'] = date('M d, Y', strtotime($article['published_at']));
            }

            return response()->json($articles);
        }

        if($request->has('query')) {
            // start with the article title
            $articles = Article::with('Employee', 'Category', 'Content', 'Related.Article')
                ->whereNull('deleted_at')
                ->where('title', 'like', '%'.$request['query'].'%')
                ->whereNotNull('published_at')
                ->where('location', $this->getStationCode())
                ->orderBy('updated_at', 'desc')
                ->simplePaginate(16);

            // article category
            if($articles->count() < 1) {
                $articles = Article::with( 'Category')->whereHas('Category', function(Builder $query) {
                    $request = request()->get('query');

                    $query->whereNull('deleted_at')->where('name', 'like', '%'.$request.'%');
                })->whereNull('deleted_at')
                    ->whereNotNull('published_at')
                    ->where('location', $this->getStationCode())
                    ->orderBy('updated_at', 'desc')
                    ->simplePaginate(16);

                // article writer
                if($articles->count() < 1) {
                    $articles = Article::with( 'Category')->whereHas('Employee', function (Builder $query){
                        $request = request()->get('query');

                        $query->whereNull('deleted_at')
                            ->where('first_name', 'like', '%'.$request.'%')
                            ->orWhere('last_name', 'like', '%'.$request.'%');
                    })->whereNull('deleted_at')
                        ->whereNotNull('published_at')
                        ->where('location', $this->getStationCode())
                        ->orderBy('updated_at', 'desc')
                        ->simplePaginate(16);
                }
            }

            $articles->appends(['sort' => $request['query']]);
        }

        foreach ($articles as $article) {
            $article['image'] = $this->verifyPhoto($article['image'], 'articles');
            $article['published_at'] = date('M d, Y', strtotime($article['published_at']));
            $article['date_updated'] = date('F d, Y', strtotime($article['updated_at']));
        }

        $categories = Category::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return response()->json([
            'articles' => $articles,
            'categories' => $categories
        ]);
    }

    public function show($articleGUID)
    {
        $article = Article::with('Employee', 'Category', 'Content', 'Related.Article.Category')
            ->where('unique_id', '=', $articleGUID)
            ->first();

        $article['image'] = $this->verifyPhoto($article['image'], 'articles');
        $article['category_name'] = $article->Category->name;
        $article['employee_name'] = $article->Employee->first_name . ' ' . $article->Employee->last_name;
        $article['date_updated'] = date('F d, Y', strtotime($article['updated_at']));
        $article['published_at'] = date('F d, Y', strtotime($article['published_at']));

        foreach ($article->Content as $content) {
            $content->image = $this->verifyPhoto($content->image, 'articles');
        }

        foreach ($article->Related as $related_article) {
            $related_article->Article->image = $this->verifyPhoto($related_article->Article->image, 'articles');
            $related_article->Article->published_at = date('F d, Y', strtotime($related_article->Article->published_at));
            $related_article->Article->author_name = $related_article->Article->Employee->first_name . ' ' . $related_article->Article->Employee->last_name;
        }

        return response()->json([
            'article' => $article
        ]);
    }
}
