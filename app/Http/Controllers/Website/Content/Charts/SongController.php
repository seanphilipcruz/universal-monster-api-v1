<?php

namespace App\Http\Controllers\Website\Content\Charts;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Song;
use App\Traits\AssetProcessors;
use App\Traits\MediaProcessors;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class SongController extends Controller
{
    use AssetProcessors, MediaProcessors;

    public function index()
    {
        $songs = Song::with('Album.Artist', 'Album.Genre')->get();
        $artists = Artist::with('Album')->get();

        $data = [
            'songs' => $songs,
            'artists' => $artists
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'album_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['is_charted'] = 0;

        if (empty($request['track_link'])) {
            $request['track_link'] = 'none';
            $song = new Song($request->all());
            $song->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.song_without_sample_save_success')
            ], 201);
        } else {
            $song = new Song($request->all());
            $song->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.song_with_spotify_save_success')
            ], 201);
        }
    }

    public function show($id)
    {
        try {
            $song = Song::with('Album.Artist')->findOrFail($id);

            if ($song->type == 'file') {
                $song->track_link = $this->verifyAudio($song->track_link);
            }
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'song' => $song
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $song = Song::with('Album.Artist')->findOrFail($id);

            $song->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Song'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $song = Song::with('Album.Artist')->findOrFail($id);

            $song->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Song'])
        ]);
    }

    public function upload_file(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required',
            'file' => ['mimes:mp3,m4a', 'file'],
            'name' => ['required', 'min:2']
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $path = 'songs';
        $directory = 'songs';

        $song_name = $this->storeFile($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' =>  $song_name
            ], 201);
        }

        $id = $request['song_id'];

        try {
            $song = Song::with('Album')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $song['type'] = 'file';
        $song['track_link'] = $song_name;
        $song->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_upload', ['Model' => 'Song'])
        ]);
    }
}
