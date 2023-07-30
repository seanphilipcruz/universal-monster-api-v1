<?php

namespace App\Http\Controllers\Website\Content\Charts;

use App\Http\Controllers\Controller;
use App\Models\Outbreak;
use App\Models\Song;
use App\Traits\ChartFunctions;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OutbreakController extends Controller
{
    use SystemFunctions, ChartFunctions;

    public function index()
    {
        $location = $this->getStationCode();

        $songs = Song::with('Album.Artist')->get();
        $outbreaks = Outbreak::with('Song.Album.Artist')
            ->where('dated', $this->getLatestOutbreakDate())
            ->whereNull('deleted_at')
            ->where('location', '=', $location)
            ->orderBy('dated', 'desc')
            ->get();

        $data = [
            'songs' => $songs,
            'outbreaks' => $outbreaks
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'song_id' => 'required',
            'dated' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $song_id = $request['song_id'];

        $song = Song::with('Album')
            ->findOrFail($song_id)
            ->select('track_link')
            ->get()
            ->first();

        if (empty($request['track_link'])) {
            $request['track_link'] = $song->track_link;
        }

        $request['location'] = $this->getStationCode();

        $outbreak = new Outbreak($request->all());
        $outbreak->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Outbreak'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $outbreak = Outbreak::with('Song.Album.Artist')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'outbreak' => $outbreak
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $outbreak = Outbreak::with('Song.Album.Artist')->findOrFail($id);

            $outbreak->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Outbreak'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $outbreak = Outbreak::with('Song.Album.Artist')->findOrFail($id);

            $outbreak->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Outbreak'])
        ]);
    }
}
