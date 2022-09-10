<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;

    public function index(Request $request)
    {
        $categories = Category::has('Article')->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $articles = Article::with('Employee', 'Category')
            ->whereNull('deleted_at')
            ->whereNotNull('published_at')
            ->where('location', $this->getStationCode())
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        // for article filtering
        $category_id = $request['category_id'];

        if($category_id) {
            $articles = Article::with('Employee', 'Category')
                ->where('category_id', $category_id)
                ->whereNotNull('published_at')
                ->where('location', $this->getStationCode())
                ->orderBy('created_at', 'desc')
                ->paginate(8);
        }

        foreach ($articles as $article) {
            $article->image = $this->verifyPhoto($article->image, 'articles');
            $article->image = $this->getAssetUrl('articles') . $article->image;
        }

        return response()->json([
            'articles' => $articles,
            'categories' => $categories,
            'next' => $articles->nextPageUrl()
        ]);
    }

    public function view($id) {
        $article = Article::with('Employee', 'Category' ,'Content', 'Relevant', 'Image')->findOrFail($id);

        $article->image = $this->verifyPhoto($article->image, 'articles');
        $article->image = $this->getAssetUrl('articles') . $article->image;

        $article->author = $article->Employee->first_name . ' ' . $article->Employee->last_name;

        return response()->json([
            'article' => $article
        ]);
    }
}
