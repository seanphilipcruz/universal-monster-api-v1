<?php

namespace App\Http\Controllers\Website\Content\Blogs;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Throwable;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all()->whereNull('deleted_at');

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $category = new Category($request->all());
        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Category'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $category = Category::with('Article')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'category' => $category
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $category = Category::with('Article')->findOrFail($id);

            $category->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Category'])
        ]);
    }
    public function destroy($id)
    {
        try {
            $category = Category::with('Article')->findOrFail($id);

            $category->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }


        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Category'])
        ]);
    }
}
