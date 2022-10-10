<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\Show;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PodcastController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;

    public function index(Request $request)
    {
        $podcasts = Podcast::with('Show')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('date', 'desc')
            ->simplePaginate(16);

        $shows = Show::with('Podcast')
            ->whereHas('Podcast', function(Builder $query) {
                $query->whereNull('deleted_at');
            })->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderBy('title')
            ->get();

        if($request->has('filter')) {
            $podcasts = Podcast::with('Show')
                ->whereHas('Show', function(Builder $query) {
                    $request = request()->get('filter');

                    $query->whereNull('deleted_at')->where('id', '=', $request);
                })->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->orderBy('date', 'desc')
                ->simplePaginate(16);

            $podcasts->appends(['filter' => $request['filter']]);
        }

        if($request->has('query')) {
            $podcasts = Podcast::with('Show')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->where('episode', 'like', '%'.$request['query'].'%')
                ->orderBy('date', 'desc')
                ->simplePaginate(16);

            if($podcasts->count() < 1) {
                $podcasts = Podcast::with('Show')
                    ->whereHas('Show', function(Builder $query) {
                        $request = request()->get('query');

                        $query->whereNull('deleted_at')->where('title', 'like', '%'.$request.'%');
                    })->whereNull('deleted_at')
                    ->where('location', $this->getStationCode())
                    ->orderBy('created_at', 'desc')
                    ->simplePaginate(16);

                $podcasts->appends(['search' => $request['query']]);
            }
        }

        foreach ($podcasts as $podcast) {
            $podcast['image'] = $this->verifyPhoto($podcast['image'], 'podcasts');
            $podcast['date_aired'] = date('F d, Y', strtotime($podcast['date']));
        }

        return response()->json([
            'podcasts' => $podcasts,
            'shows' => $shows
        ]);
    }

    public function show($id) {
        $podcast = Podcast::with('Show')->findOrFail($id);

        $related_podcast = Podcast::with('Show')
            ->whereNull('deleted_at')
            ->where('show_id', '=', $podcast['show_id'])
            ->where('id', '!=', $podcast['id'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->take(8);

        foreach ($related_podcast as $related) {
            $related['show_title'] = $related->Show->title;
            $related['date_aired'] = date('F d, Y', strtotime($related['date']));
            $related['image'] = $this->verifyPhoto($related['image'], 'podcasts');
        }

        $podcast['image'] = $this->verifyPhoto($podcast['image'], 'podcasts');
        $podcast['show_title'] = $podcast->Show->title;
        $podcast['show_description'] = $podcast->Show->front_description;
        $podcast['date_aired'] = date('F d, Y', strtotime($podcast['date']));

        return response()->json([
            'podcast' => $podcast,
            'related' => $related_podcast
        ]);
    }
}
