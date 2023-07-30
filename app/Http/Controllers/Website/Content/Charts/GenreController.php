<?php

namespace App\Http\Controllers\Website\Content\Charts;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::whereNull('deleted_at')->get();

        return response()->json([
            'genres' => $genres
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $genre = new Genre($request->all());
        $genre->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Genre'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $genre = Genre::findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'genre' => $genre
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $genre = Genre::findOrFail($id);

            $genre->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Genre'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $genre = Genre::with('Album')->findOrFail($id);

            $genre->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Genre'])
        ]);
    }
}
