<?php

namespace App\Http\Controllers\Website\Content\Indieground;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Indie;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ArtistController extends Controller
{
    use SystemFunctions;

    public function index()
    {
        $artists = Artist::with('Album')
            ->whereDoesntHave('Indie')
            ->orderBy('name')
            ->get();

        $indie_artists = Indie::with('Artist')
            ->whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->get();

        $data = [
            'indie_artists' => $indie_artists,
            'artists' => $artists
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'artist_id' => 'required',
            'introduction' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $id = $request['artist_id'];
        $artist = Artist::with('Album')->findOrFail($id);
        $request['image'] = $artist['image'];
        $request['location'] = $this->getStationCode();

        $indie = new Indie($request->all());
        $indie->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Indie'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $indie = Indie::with('Artist.Album.Song')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'indie' => $indie
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $indie = Indie::with('Artist.Album.Song')->findOrFail($id);

            $indie->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Indie'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $indie = Indie::with('Artist.Album.Song')->findOrFail($id);

            $indie->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Indie'])
        ]);
    }
}
