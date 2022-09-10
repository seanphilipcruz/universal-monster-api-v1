<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Models\Feature;
use App\Models\Indie;
use App\Models\Outbreak;
use App\Models\Show;
use App\Models\Song;
use App\Traits\ChartFunctions;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChartController extends Controller
{
    use SystemFunctions;
    use ChartFunctions;
    use MediaProcessors;

    public function index(Request $request)
    {
        $session_id = $this->generateUniqueID(10);

        $chart_title = $this->getStationChart();

        $station_chart_id = $this->getStationCode() === 'mnl' ? 17 : ($this->getStationCode() === 'cbu' ? 38 : 29);

        $result_date = date('F d, Y', strtotime($this->getLatestChartDate()));

        // Todo: Get the countdown according to station
        // Id: 17 is Monster Hitlist / Countdown Top 7
        $hit_list = Show::with('Jock')
            ->whereNull('deleted_at')
            ->findOrFail($station_chart_id);

        $hit_list->header_image = $this->verifyPhoto($hit_list->header_image, 'shows');
        $hit_list->background_image = $this->verifyPhoto($hit_list->background_image, 'shows');

        $charts = Chart::with('Song.Album.Artist', 'Song.Album.Genre')
            ->whereNull('deleted_at')
            ->where('dated', $this->getLatestChartDate())
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get();

        if($request['type'] == 'top5') {
            $charts = Chart::with('Song.Album.Artist')
                ->whereNull('deleted_at')
                ->where('dated', $this->getLatestChartDate())
                ->where('daily', 0)
                ->where('is_posted', 1)
                ->where('location', $this->getStationCode())
                ->orderBy('position')
                ->get()
                ->take(5);
        }

        if($request->has('chart_id')) {
            $votedChart = Chart::with('Song.Album.Artist')->findOrFail($request['chart_id']);

            ++$votedChart->online_votes;
            $votedChart['voted_at'] = date('Y-m-d');
            $votedChart->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Vote sent!'
            ], 201);
        }

        foreach ($charts as $chart) {
            $chart['artist_name'] = $chart->Song->Album->Artist->name;
            $chart['album_name'] = $chart->Song->Album->name;
            $chart['album_image'] = $chart->Song->Album->image;
            $chart['song_name'] = $chart->Song->name;
            $chart['genre_name'] = $chart->Song->Album->Genre->name;

            $chart->Song->Album->image = $this->verifyPhoto($chart->Song->Album->image, 'albums');
        }

        return response()->json([
            'charts' => $charts,
            'result_date' => $result_date,
            'session_id' => $session_id,
            'show' => $hit_list,
            'chart_title' => $chart_title
        ]);
    }

    public function daily(Request $request)
    {
        $result_date = "";

        $chart_dates = Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('daily', 1)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->groupBy('dated')
            ->get();

        foreach ($chart_dates as $dates) {
            $dates['date'] = date('F d, Y', strtotime($dates['dated']));
        }

        $daily = Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('dated', $this->getLatestDailyChartDate())
            ->where('daily', 1)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get();

        $monster_hit_list = Show::with('Jock')->findOrFail(4); // 17 is The Monster Hit List, 4 is The Daily Survey

        $monster_hit_list->header_image = $this->verifyPhoto($monster_hit_list->header_image, 'shows');
        $monster_hit_list->background_image = $this->verifyPhoto($monster_hit_list->background_image, 'shows');

        if($request['date']) {
            $daily = Chart::with('Song.Album.Artist')
                ->whereNull('deleted_at')
                ->where('dated', $request['date'])
                ->where('daily', 1)
                ->where('is_posted', 1)
                ->where('location', $this->getStationCode())
                ->orderBy('position')
                ->get();
        }

        foreach ($daily as $chart) {
            $result_date = date('F d, Y', strtotime($chart['dated']));
        }

        return response()->json([
            'daily_charts' => $daily,
            'result_date' => $result_date,
            'dates' => $chart_dates,
            'show' => $monster_hit_list
        ]);
    }

    public function allTimeHit(Request $request)
    {
        $chart_dates = Chart::with('Song')
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->select('dated')
            ->orderBy('dated', 'desc')
            ->groupBy('dated')
            ->get();

        foreach ($chart_dates as $date) {
            $date['date'] = date('F d, Y', strtotime($date['dated']));
        }

        $currentHit = Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('dated', $this->getLatestChartDate())
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get()
            ->first();

        $chart = Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('dated', $this->getLatestChartDate())
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get()
            ->first();

        $chart['chart_date'] = date('F d, Y', strtotime($chart['dated']));

        if($request['date']) {
            $currentHit = Chart::with('Song.Album.Artist')
                ->whereNull('deleted_at')
                ->where('dated', $request['date'])
                ->where('daily', 0)
                ->where('is_posted', 1)
                ->where('location', $this->getStationCode())
                ->orderBy('position')
                ->get()
                ->first();

            if (!$currentHit) {
                $chart->Song->Album->image = $this->verifyPhoto($chart->Song->Album->image, 'albums');

                return response()->json([
                    'monster_hit' => $chart,
                    'current_hit' => $currentHit,
                    'dates' => $chart_dates
                ]);
            }
        }

        $currentHit['chart_date'] = date('F d, Y', strtotime($currentHit['dated']));

        $chart->Song->Album->image = $this->verifyPhoto($chart->Song->Album->image, 'albums');
        $currentHit->Song->Album->image = $this->verifyPhoto($currentHit->Song->Album->image, 'albums');

        return response()->json([
            'monster_hit' => $chart,
            'current_hit' => $currentHit,
            'dates' => $chart_dates
        ]);
    }

    public function indieground(Request $request)
    {
        $featured = Feature::with('Indie.Artist.Album.Song')
            ->whereNull('deleted_at')
            ->get();

        $indieground = Indie::with(['Artist' => function($query) {
            $query->with(['Album' => function($query) {
                $query->with(['Song' => function($query) {
                    $query->latest()->whereNull('deleted_at')->orderBy('created_at');
                }])->has('Song');
            }]);
        }])->has('Artist.Album.Song')
            ->latest()
            ->whereNull('deleted_at')
            ->get();

        if($request->has('indieground_id')) {
            $indieground_artist = Indie::with(['Artist' => function($query) {
                $query->with(['Album' => function($query) {
                    $query->with(['Song' => function($query) {
                        $query->latest()->whereNull('deleted_at')->orderBy('created_at');
                    }]);
                }]);
            }])->findOrFail($request['indieground_id']);

            $indieground_artist->image = $this->verifyPhoto($indieground_artist->image, 'indie');
            $indieground_artist['slug_artist_name'] = Str::slug($indieground_artist->Artist->name);
            $indieground_artist->Artist->image = $this->verifyPhoto($indieground_artist->Artist->image, 'artists');

            foreach ($indieground_artist->Artist->Album as $album) {
                $album->image = $this->verifyPhoto($album->image, 'albums');

                foreach ($album->Song as $song) {
                    if ($song->type === 'mp3/m4a') {
                        $song->track_link = $this->verifyAudio($song->track_link);
                    }
                }
            }

            foreach ($indieground as $artist) {
                $artist->image = $this->verifyPhoto($artist->image, 'indie');
                $artist->slug_artist_name = Str::slug($artist->Artist->name);
                $artist->Artist->image = $this->verifyPhoto($artist->Artist->image, 'artists');
            }

            return response()->json([
                'indieground_artists' => $indieground,
                'artist' => $indieground_artist
            ]);
        }

        foreach($indieground as $indieground_artist) {
            $indieground_artist['slug_artist_name'] = Str::slug($indieground_artist->Artist->name);
            $indieground_artist->image = $this->verifyPhoto($indieground_artist->image, 'indie');

            foreach ($indieground_artist->Artist->Album as $album) {
                $album->image = $this->verifyPhoto($album->image, 'albums');

                foreach ($album->Song as $song) {
                    if ($song->type === 'mp3/m4a') {
                        $song->track_link = $this->verifyAudio($song->track_link);
                    }
                }
            }
        }

        return response()->json([
            'featured' => $featured,
            'indiegrounds' => $indieground
        ]);
    }

    // for when the user clicks an indieground song.
    public function getSongData($id)
    {
        $song = Song::with('Album.Artist')->findOrFail($id);

        if ($song->type === 'mp3/m4a') {
            $song->track_link = $this->verifyAudio($song->track_link);
        }

        $song->Album->image = $this->verifyPhoto($song->Album->image, 'albums');
        $song->Album->Artist->image = $this->verifyPhoto($song->Album->Artist->image, 'artists');

        return response()->json([
            'song' => $song
        ]);
    }

    public function southside()
    {
        $southside = Show::with('Timeslot', 'Jock')->findOrFail(35); // Southside Sounds

        $southsideCharts = Chart::with('Song.Album.Artist')->whereNull('deleted_at')
            ->where('dated', $this->getLatestSouthsidesDate())
            ->where('local', '=', 1)
            ->where('position', '>', 0)
            ->where('location', '=', $this->getStationCode())
            ->orderBy('position')
            ->get();

        return response()->json([
            'show' => $southside,
            'charts' => $southsideCharts
        ]);
    }

    public function outbreaks()
    {
        $outbreaks = Outbreak::with('Song.Album.Artist')->whereNull('deleted_at')
            ->where('dated', $this->getLatestChartDate())
            ->where('location', '=', $this->getStationCode())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($outbreaks);
    }
}
