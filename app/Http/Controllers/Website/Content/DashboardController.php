<?php

namespace App\Http\Controllers\Website\Content;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Bugs;
use App\Models\Chart;
use App\Models\Employee;
use App\Models\Message;
use App\Models\Podcast;
use App\Traits\AssetProcessors;
use App\Traits\ChartFunctions;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use AssetProcessors;
    use ChartFunctions;
    use SystemFunctions;

    public function index() {
        $charts = Chart::with('Song.Album.Artist')
            ->whereNull('deleted_at')
            ->where('daily', 0)
            ->where('local', 0)
            ->where('location', $this->getStationCode())
            ->orderBy('dated', 'desc')
            ->orderBy('position')
            ->get()
            ->take(10);

        $podcasts = Podcast::with('Show')
            ->whereNull('deleted_at')
            ->orderBy('date')
            ->get()
            ->take(5);

        $messages = Message::where('is_seen', '=', 0)
            ->whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->get();

        $draftedArticles = Article::with('Category', 'Employee')
            ->whereNull('deleted_at')
            ->whereNull('published_at')
            ->where('location', '=', $this->getStationCode())
            ->get()
            ->take(5);

        $articles = Article::with('Category', 'Employee')
            ->whereNull('deleted_at')
            ->whereNotNull('published_at')
            ->where('location', '=', $this->getStationCode())
            ->get()
            ->take(5);

        $bugs = Bugs::all()->where('is_resolved', '=', 0);

        $data = [
            'charts' => $charts,
            'podcasts' => $podcasts,
            'messages' => $messages,
            'draftedArticles' => $draftedArticles,
            'articles' => $articles,
            'bugs' => $bugs
        ];

        return response()->json($data);
    }
}
