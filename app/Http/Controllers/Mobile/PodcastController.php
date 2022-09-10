<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\Show;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PodcastController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;

    public function index(Request $request)
    {
        $shows = Show::has('Podcast')->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('title')
            ->get();

        $podcasts = Podcast::with('Show')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('created_at', 'desc')
            ->simplePaginate(8);

        // for podcast filtering
        $show_id = $request['show_id'];

        if($show_id) {
            $podcasts = Podcast::with('Show')
                ->whereNull('deleted_at')
                ->where('show_id', $show_id)
                ->where('location', $this->getStationCode())
                ->orderBy('created_at', 'desc')
                ->simplePaginate(8);

            $podcasts->appends('show_id', $show_id);
        }

        foreach ($podcasts as $podcast) {
            $podcast['episode'] = Str::limit($podcast['episode'], 16);
            $podcast['image'] = $this->verifyPhoto($podcast['image'], 'podcasts');
            $podcast['image'] = $this->getAssetUrl('podcasts') . $podcast['image'];
        }

        return response()->json([
            'shows' => $shows,
            'podcasts' => $podcasts,
            'next' => $podcasts->nextPageUrl()
        ]);
    }

    public function view($id)
    {
        $podcasts = Podcast::with('Show')->findOrFail($id);

        return response()->json([
            'podcast' => $podcasts
        ]);
    }
}
