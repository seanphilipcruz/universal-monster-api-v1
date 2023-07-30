<?php

namespace App\Http\Controllers\Website\Content\Charts;

use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Models\Song;
use App\Traits\ChartFunctions;
use Illuminate\Http\Request;

class SouthsideController extends Controller
{
    use ChartFunctions;

    public function index()
    {
        $southsides = Chart::with('Song.Album.Artist')
            ->where('dated', '=', $this->getLatestSouthsidesDate())
            ->whereNull('deleted_at')
            ->where('local', '=', 1)
            ->where('position', '>', 0)
            ->where('location', '=', $this->getStationCode())
            ->orderBy('position')
            ->get();

        $songs = Song::with('Album.Artist')->latest()->get();

        $data = [
            'southsides' => $southsides,
            'songs' => $songs
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        // Refer to store() in ChartController.php
    }
}
