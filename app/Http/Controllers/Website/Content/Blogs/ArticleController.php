<?php

namespace App\Http\Controllers\Website\Content\Blogs;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Traits\AssetProcessors;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ArticleController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $year = date('Y');
        $articles = Article::with('Category', 'Employee')
            ->whereYear('created_at', '=', $year)
            ->get();
        $categories = Category::all();

        $data = [
            'articles' => $articles,
            'categories' => $categories
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'heading' => 'required|min:20',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
         }

        // Article GUID
        $request['unique_id'] = $this->IdGenerator(8);
        $request['location'] = $this->getStationCode();

        $article = new Article($request->all());

        $article->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'article'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $article = Article::with('Category')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'article' => $article
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $article = Article::with('Category')->findOrFail($id);

            $article->update($request->except('image'));
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'article'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $article = Article::with('Related')->findOrFail($id);

            $article->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'article'])
        ]);
    }

    // Added a separate image uploader.
    public function upload_image(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required',
            'image' => ['mimes:jpg,jpeg,png,webp', 'file']
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $path = 'images/articles';
        $directory = 'articles';

        $image_name = $this->storePhoto($request, $path, $directory);

        // Determine if the request is store (for new images).
        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['article_id'];

        try {
            $article = Article::with('Category')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $article['image'] = $image_name;
        $article->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'article'])
        ]);
    }
}
