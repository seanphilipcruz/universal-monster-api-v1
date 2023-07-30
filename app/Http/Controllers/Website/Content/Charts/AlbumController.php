<?php

namespace App\Http\Controllers\Website\Content\Charts;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Traits\AssetProcessors;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AlbumController extends Controller
{
    use AssetProcessors;

    public function index()
    {
        $albums = Album::with('Artist', 'Song', 'Genre')->get();

        $data = [
            'albums' => $albums,
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'artist_id' => 'required',
            'genre_id' => 'required',
            'name' => 'required',
            'type' => 'required',
            'year' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $album = new Album($request->all());
        $album->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Album'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $album = Album::with('Artist')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'album' => $album
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $album = Album::with('Artist')->findOrFail($id);

            $album->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Album'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $album = Album::with('Artist')->findOrFail($id);

            $album->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Album'])
        ]);
    }

    public function upload_image(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required',
            'image' => ['mimes:jpg,jpeg,png,webp', 'file']
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $path = 'images/albums';
        $directory = 'albums';

        $image_name = $this->storePhoto($request, $path, $directory, true);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' =>  $image_name
            ], 201);
        }

        $id = $request['album_id'];

        try {
            $album = Album::with('Song')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $album['image'] = $image_name;
        $album->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_upload', ['Model' => 'Album'])
        ]);
    }
}
