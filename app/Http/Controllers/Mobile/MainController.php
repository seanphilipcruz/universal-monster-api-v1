<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Chart;
use App\Models\Contest;
use App\Models\Mobile\Content\Asset;
use App\Models\Podcast;
use App\Models\Show;
use App\Models\Timeslot;
use App\Traits\ChartFunctions;
use App\Traits\MediaProcessors;
use App\Traits\JockFunctions;
use App\Traits\SystemFunctions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SebastianBergmann\Type\Exception;

class MainController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;
    use ChartFunctions;
    use JockFunctions;

    public function index()
    {
        $day = Carbon::now()->format('l');
        $time = date('H:i');

        $session_id = $this->IdGenerator(10);

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

        $articles = Article::with('Employee')->latest()
            ->whereNull('deleted_at')
            ->whereNotNull('published_at')
            ->where('location', $this->getStationCode())
            ->orderBy('published_at', 'desc')
            ->get()
            ->take(5);

        $podcasts = Podcast::with('Show')
            ->latest()
            ->whereNull('deleted_at')
            ->where('show_id', 1) // The Morning Rush
            ->where('location', $this->getStationCode())
            ->get()
            ->take(5);

        $currentShow = Show::with('Timeslot')
            ->whereHas('Timeslot', function ($query) {
                $day = Carbon::now()->format('l');
                $time = date('H:i');

                $query->whereNull('deleted_at')
                    ->where('end', '>', $time)
                    ->where('start', '<=', $time)
                    ->where('day', '=', $day)
                    ->where('location', $this->getStationCode())
                    ->orderBy('start');
            })->whereNull('deleted_at')
            ->first();

        $chart_date = date('F d, Y', strtotime($charts->first()->dated));

        $jocks = $this->jocksQuery($time, $day);

        foreach ($charts as $chart) {
            $chart->Song->Album->image = $this->getAssetUrl('albums') . $chart->Song->Album->image;
        }

        foreach ($articles as $article) {
            $article->image = $this->verifyPhoto($article['image'], 'articles');
            $article->image = $this->getAssetUrl('articles') . $article->image;
        }

        foreach ($podcasts as $podcast) {
            $podcast->image = $this->verifyPhoto($podcast['image'], 'podcasts');
            $podcast->image = $this->getAssetUrl('podcasts') . $podcast->image;
        }

        foreach ($jocks as $jock) {
            $jock->profile_image = $this->verifyPhoto($jock->profile_image, 'jocks');
            $jock->profile_image = $this->getAssetUrl('jocks') . $jock->profile_image;
        }

        if ($currentShow) {
            $currentShow->background_image = $this->verifyPhoto($currentShow->background_image, 'shows');
            $currentShow->background_image = $this->getAssetUrl('shows') . $currentShow->background_image;

            return response()->json([
                'giveaways' => $giveaway,
                'charts' => $charts,
                'articles' => $articles,
                'podcasts' => $podcasts,
                'chart_date' => $chart_date,
                'jocks' => $jocks,
                'session_id' => $session_id
            ]);
        }

        return response()->json([
            'giveaways' => $giveaway,
            'charts' => $charts,
            'articles' => $articles,
            'podcasts' => $podcasts,
            'chart_date' => $chart_date,
            'show' => $currentShow,
            'jocks' => $jocks,
            'session_id' => $session_id
        ]);
    }

    public function live()
    {
        // Variables on getting the day and time
        $day = Carbon::now()->format('l');
        $time = date('H:i');

        // 20240419 Update
        $stream = 'https://in-icecast.eradioportal.com:8443/monsterrrx';

        $currentShow = Show::with('Timeslot', 'Jock.Employee')
            ->whereHas('Timeslot', function($query) {
                $day = Carbon::now()->format('l');
                $time = date('H:i');

                $query->whereNull('deleted_at')
                    ->where('end', '>', $time)
                    ->where('start', '<=', $time)
                    ->where('day', '=', $day)
                    ->where('location', $this->getStationCode())
                    ->orderBy('start');
            })->whereNull('deleted_at')
            ->first();

        $showList = Timeslot::with('Show')
            ->whereNull('deleted_at')
            ->where('day', $day)
            ->where('location', $this->getStationCode())
            ->orderBy('start')
            ->get();

        foreach ($showList as $timeslot) {
            $timeslot['start'] = date('h:i A', strtotime($timeslot['start']));
            $timeslot['end'] = date('h:i A', strtotime($timeslot['end']));
        }

        if($currentShow === null) {
            return response()->json(['show' => $currentShow, 'live' => $stream]);
        }

        $show_id = $currentShow['id'];

        switch ($show_id) {
            case '1' || (1 && $day === 'Tuesday' || $day === 'Friday'): {
                $currentJocks = $this->removeTMRJock(26, $time, $day); // Markki Stroem
                // return response()->json(['jocks' => $currentJocks, 'timeslots' => $showList, 'show' => $currentShow, 'podcasts' => $podcasts, 'live' => $stream]);
            }
            case '1' || (1 && $day === 'Wednesday'): {
                $currentJocks = $this->removeTMRJock(4, $time, $day); // Rica G. or Rica Garcia
                // return response()->json(['jocks' => $currentJocks, 'timeslots' => $showList, 'show' => $currentShow, 'podcasts' => $podcasts, 'live' => $stream]);
            }
            default: {
                $currentJocks = $this->jocksQuery($time, $day);

                return response()->json(['show' => $currentShow, 'jocks' => $currentJocks, 'live' => $stream]);
            }
        }
    }

    public function youTube($max) {
        $channel_id = 'UCMgKa-bzBoj40sQUqvX7kag';
        $channel_key = 'AIzaSyDv1JDmiKR1QiLeKaUIWtWTA45Ay-cUmyk'; // AIzaSyAr-GrOSqC0o3-d-DPbkUM4E7kOZ76KRNA
        $channel_max = $max;

        $data = array('id' => $channel_id, 'key' => $channel_key, 'max' => $channel_max);

        return response()->json($data);
    }

    public function browse(Request $request) {
        $search = $request->keyword;

        $podcasts = Podcast::with('Show')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('date', 'desc')
            ->get()
            ->take(5);

        $articles = Article::with('Category', 'Employee')
            ->whereNull('deleted_at')
            ->whereNotNull('published_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(5);

        if($search) {
            $podcasts = Podcast::with('Show')
                ->whereNull('deleted_at')
                ->where('episode', 'like', '%'.$search.'%')
                ->orderByDesc('created_at')
                ->simplePaginate(5);

            $articles = Article::whereNull('deleted_at')
                ->whereNotNull('published_at')
                ->where('title', 'like', '%'.$search.'%')
                ->orWhere('heading', 'like', '%'.$search.'%')
                ->orderByDesc('created_at')
                ->simplePaginate(5);
        }

        foreach ($podcasts as $podcast) {
            $podcast['image'] = $this->verifyPhoto($podcast['image'], 'podcasts');
            $podcast['image'] = $this->getAssetUrl('podcasts') . $podcast['image'];
        }

        foreach ($articles as $article) {
            $article['image'] = $this->verifyPhoto($article['image'], 'articles');
            $article['image'] = $this->getAssetUrl('articles') . $article['image'];
        }

        return response()->json(['podcasts' => $podcasts, 'articles' => $articles]);
    }

    public function search() {
        return view('components.search');
    }

    public function help() {
        return view('help');
    }

    public function about() {
        return view('about');
    }
}
