<?php

namespace App\Http\Controllers\Website\Content\Charts;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ArtistController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $artists = Artist::with('Album')->get();
        $countries = $this->getCountries();

        $data = [
            'artists' => $artists,
            'countries' => $countries
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'country' => 'required',
            'type' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $artist = new Artist($request->all());
        $artist->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Artist'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $artist = Artist::with('Album.Song')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'artist' => $artist
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $artist = Artist::with('Album.Song')->findOrFail($id);

            $artist->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Artist'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $artist = Artist::with('Album.Song')->findOrFail($id);

            $artist->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Artist'])
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

        $path = 'image/artists';
        $directory = 'artists';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['artist_id'];

        try {
            $artist = Artist::with('Album')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $artist['image'] = $image_name;
        $artist->save();

        return response()->json([
            'status' => 'error',
            'message' => __('responses.success_image_upload', ['Model' => 'Artist'])
        ]);
    }
}
