<?php

namespace App\Http\Controllers\Website\Content\Blogs;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SubArticleContentController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'article_id' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $id = $request['article_id'];
        $content = new Content($request->all());
        $content->Article()->associate($id);
        $content->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.content_add_success', ['Model' => 'Content'])
        ], 201);
    }

    public function show(Content $content)
    {
        return response()->json([
            'content' => $content
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $content = Content::with('Article')->findOrFail($id);

            $content->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Content'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $content = Content::with('Article')->findOrFail($id);

            $content->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Content'])
        ]);
    }
}
