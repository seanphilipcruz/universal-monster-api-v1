<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Models\Show;
use App\Traits\ChartFunctions;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;
    use ChartFunctions;

    public function index(Request $request)
    {
        $shows = Show::with('Jock')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->where('is_special', '=', 0)
            ->where('is_active', '=', 1)
            ->get();

        if($request['type'] == 'specials') {
            $shows = Show::with('Jock')
                ->whereNull('deleted_at')
                ->where('location', $this->getStationCode())
                ->where('is_special', '=', 1)
                ->where('is_active', '=', 1)
                ->get();
        }

        foreach ($shows as $show) {
            $show['icon'] = $this->verifyPhoto($show['icon'], 'shows');
            $show['header_image'] = $this->verifyPhoto($show['header_image'], 'shows');
            $show['background_image'] = $this->verifyPhoto($show['background_image'], 'shows');
        }

        return response()->json([
            'shows' => $shows
        ]);
    }

    public function view($slugString) {
        $show_id = Show::with(['Jock', 'Image', 'Timeslot' => function($query) {
            $query->where('location', $this->getStationCode());
        }, 'Podcast' => function($query) {
            $query->latest()->take(5);
        }])->where('slug_string', '=', $slugString)
        ->firstOrFail()->id;

        $show = Show::with(['Jock', 'Image', 'Timeslot' => function($query) {
            $query->where('location', $this->getStationCode());
        }, 'Podcast' => function($query) {
            $query->latest()->take(5);
        }])->findOrFail($show_id);

        $show['icon'] = $this->verifyPhoto($show['icon'], 'shows');
        $show['header_image'] = $this->verifyPhoto($show['header_image'], 'shows');
        $show['background_image'] = $this->verifyPhoto($show['background_image'], 'shows');

        foreach ($show->Jock as $jock) {
            $jock['profile_image'] = $this->verifyPhoto($jock['profile_image'], 'jocks');
            $jock['background_image'] = $this->verifyPhoto($jock['background_image'], 'jocks');
            $jock['main_image'] = $this->verifyPhoto($jock['main_image'], 'jocks');
        }

        foreach ($show->Image as $image) {
            $image['file'] = $this->verifyPhoto($image['file'], 'shows');
        }

        foreach($show->Timeslot as $timeslot) {
            $timeslot['starting'] = date('h:i A', strtotime($timeslot['start']));
            $timeslot['ending'] = date('h:i A', strtotime($timeslot['end']));
        }

        foreach ($show->Podcast as $podcast) {
            $podcast['image'] = $this->verifyPhoto($podcast['image'], 'podcasts');
            $podcast['date_aired'] = date('F d, Y', strtotime($podcast['date']));
        }

        return response()->json([
            'show' => $show
        ]);
    }
}
