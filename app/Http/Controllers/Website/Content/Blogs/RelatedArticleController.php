<?php

namespace App\Http\Controllers\Website\Content\Blogs;

use App\Http\Controllers\Controller;
use App\Models\Relevant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RelatedArticleController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article_id' => 'required',
            'related_article_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $article_id = $request['article_id'];
        $related_article_id = $request['related_article_id'];

        $related_articles = Relevant::with('Article')
            ->where('article_id', $article_id)
            ->where('related_article_id', $related_article_id)
            ->count();

        if ($related_articles > 0) {
            return response()->json([
                'status' => 'error',
                'message' => __('responses.failed_add_related', ['Model' => 'Article'])
            ], 400);
        }

        $related = new Relevant($request->all());
        $related->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_added_related', ['Model' => 'Article'])
        ]);
    }

    public function show(Relevant $relevant)
    {
        //
    }

    public function update(Request $request, Relevant $relevant)
    {
        //
    }

    public function destroy($id)
    {
        try {
            $relevant = Relevant::with('Article')->findOrFail($id);

            $relevant->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_removed_related', ['Model' => 'Article'])
        ]);
    }
}
