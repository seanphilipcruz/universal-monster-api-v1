<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Batch;
use App\Models\Chart;
use App\Models\Contest;
use App\Models\Gimmick;
use App\Models\Header;
use App\Models\Podcast;
use App\Models\Show;
use App\Models\Timeslot;
use App\Models\Wallpaper;
use App\Traits\ChartFunctions;
use App\Traits\MediaProcessors;
use App\Traits\JockFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    use MediaProcessors;
    use JockFunctions;
    use ChartFunctions;

    public function home(Request $request)
    {
        $giveaway = Contest::whereNull('deleted_at')
            ->orderBy('type')
            ->where('is_active', 1)
            ->where('location', $this->getStationCode())
            ->get();

        $charts = Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('dated', $this->getLatestChartDate())
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get()
            ->take(5);

        $headers = Header::whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('number')
            ->get();

        $articles = Article::with('Employee')->latest()
            ->whereNull('deleted_at')
            ->whereNotNull('published_at')
            ->where('location', $this->getStationCode())
            ->orderBy('published_at', 'desc')
            ->first();

        $articles ? $articles['image'] = $this->verifyPhoto($articles['image'], 'articles') : $articles = null;

        $tmr = Podcast::with('Show')
            ->latest()
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->where('show_id', '=', 1)
            ->first();

        $tmr ? $tmr['image'] = $this->verifyPhoto($tmr['image'], 'podcasts') : $tmr = null;

        $podcasts = Podcast::with('Show')
            ->latest()
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->where('show_id', '!=', 1)
            ->first();

        $podcasts ? $podcasts['image'] = $this->verifyPhoto($podcasts['image'], 'podcasts') : $podcasts = null;

        $chart_date = date('F d, Y', strtotime($charts->first()->dated));

        foreach ($headers as $header) {
            $header->image = $this->verifyPhoto($header['image'], 'headers');
        }

        foreach ($charts as $chart) {
            $chart->Song->Album->image = $this->verifyPhoto($chart->Song->Album->image, 'albums');
        }

        $version = $this->getAppVersion();
        $station = $this->getStationCode();
        $stationId = ($station === 'mnl' ? 17 : ($station === 'cbu' ? 38 : 29));

        $chart = Show::with('Jock')->findOrFail($stationId);

        $events = Gimmick::with('School')
            ->whereNull('deleted_at')
            ->whereRaw('YEAR(start_date) = ?', [date('Y')])
            ->orderBy('start_date', 'desc')
            ->latest()
            ->get();

        $scholars = Batch::with('Student', 'Sponsor')
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return response()->json([
            'giveaways' => $giveaway,
            'charts' => $charts,
            'articles' => $articles,
            'headers' => $headers,
            'tmr' => $tmr,
            'podcasts' => $podcasts,
            'chart_date' => $chart_date,
            'appVersion' => $version,
            'gimikboards' => $events,
            'monsterScholars' => $scholars,
            'stationCode' => $station,
            'stationChart' => $chart,
            'stationName' => $this->getStationName()
        ]);
    }

    public function liveStream()
    {
        // Variables on getting the day and time
        $day = Carbon::now()->format('l');
        $date = date('F d, Y');
        $getTime = Carbon::now('Asia/Manila');
        $time = date('H:i', strtotime($getTime));

        $stream = 'https://ph-icecast-win.eradioportal.com:8443/monsterrx'; // 'https://sg-icecast.eradioportal.com:8443/monsterrx' temporary link in-case 'http://ph-icecast-win.eradioportal.com:8000/monsterrx'

        $current_show = Show::with('Timeslot.Jock')
            ->whereHas('Timeslot', function($query) {
                $day = Carbon::now()->format('l');
                $getTime = Carbon::now('Asia/Manila');
                $time = date('H:i', strtotime($getTime));

                $query->whereNull('deleted_at')
                    ->where('end', '>', $time)
                    ->where('start', '<=', $time)
                    ->where('day', '=', $day)
                    ->where('location', $this->getStationCode());
            })->whereNull('deleted_at')
            ->first();

        $show_list = Timeslot::with('Show', 'Jock.Timeslot')
            ->whereNull('deleted_at')
            ->where('day', $day)
            ->where('location', $this->getStationCode())
            ->orderBy('start')
            ->get();

        foreach ($show_list as $timeslot) {
            $timeslot['start'] = date('h:i A', strtotime($timeslot['start']));
            $timeslot['end'] = date('h:i A', strtotime($timeslot['end']));
        }

        $podcasts = Podcast::with('Show')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(4);

        foreach ($podcasts as $podcast) {
            $podcast->image = $this->verifyPhoto($podcast->image, 'podcasts');
        }

        if($current_show === null) {
            $current_jocks = $this->jocksQuery();

            foreach ($current_jocks as $jock) {
                $jock->profile_image = $this->verifyPhoto($jock->profile_image, 'jocks');
                $jock->background_image = $this->verifyPhoto($jock->background_image, 'jocks');
                $jock->main_image = $this->verifyPhoto($jock->main_image, 'jocks');
            }

            return response()->json([
                'jocks' => $current_jocks,
                'timeslots' => $show_list,
                'show' => $current_show,
                'podcasts' => $podcasts,
                'live' => $stream,
                'day' => $day,
                'date' => $date
            ]);
        }

        $current_show->header_image = $this->verifyPhoto($current_show->header_image, 'shows');
        $current_show->background_image = $this->verifyPhoto($current_show->background_image, 'shows');

        $show_id = $current_show['id'];

        switch ($show_id) {
            case '1' || (1 && $day === 'Tuesday' || $day === 'Friday'): {
                $current_jocks = $this->removeTMRJock(26, $time, $day); // Markki Stroem
                // return response()->json(['jocks' => $currentJocks, 'timeslots' => $showList, 'show' => $currentShow, 'podcasts' => $podcasts, 'live' => $stream]);
            }
            case '1' || (1 && $day === 'Wednesday'): {
                $current_jocks = $this->removeTMRJock(4, $time, $day); // Rica G. or Rica Garcia
                // return response()->json(['jocks' => $currentJocks, 'timeslots' => $showList, 'show' => $currentShow, 'podcasts' => $podcasts, 'live' => $stream]);
            }
            default: {
                $current_jocks = $this->jocksQuery();

                foreach ($current_jocks as $jock) {
                    $jock->profile_image = $this->verifyPhoto($jock->profile_image, 'jocks');
                    $jock->background_image = $this->verifyPhoto($jock->background_image, 'jocks');
                    $jock->main_image = $this->verifyPhoto($jock->main_image, 'jocks');
                }

                return response()->json([
                    'jocks' => $current_jocks,
                    'timeslots' => $show_list,
                    'show' => $current_show,
                    'podcasts' => $podcasts,
                    'live' => $stream,
                    'day' => $day,
                    'date' => $date
                ]);
            }
        }
    }

    public function wallpapers(Request $request) {
        $wallpapers = Wallpaper::latest()
            ->where('location', $this->getStationCode())
            ->whereNull('deleted_at')
            ->where('device', '=', 'mobile')
            ->simplePaginate(16);

        foreach ($wallpapers as $wallpaper) {
            $wallpaper['device'] = ucfirst($wallpaper['device']);
            $wallpaper['image'] = $this->verifyPhoto($wallpaper['image'], 'wallpapers', false, false, false, true);
        }

        if($request->has('type')) {
            if($request['type'] === 'web') {
                $wallpapers = Wallpaper::latest()->where('location', $this->getStationCode())
                    ->whereNull('deleted_at')
                    ->where('device', '=', 'web')
                    ->simplePaginate(16);

                foreach ($wallpapers as $wallpaper) {
                    $wallpaper['device'] = ucfirst($wallpaper['device']);
                    $wallpaper['image'] = $this->verifyPhoto($wallpaper['image'], 'wallpapers', false, false, false, false, true);
                }
            }

            $wallpapers->appends(['type' => $request['type']]);
        }

        return response()->json([
            'wallpapers' => $wallpapers
        ]);
    }
}
