<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Gimmick;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Support\Str;

class EventController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;

    public function index()
    {
        $year = date('Y');

        $events = Gimmick::with('School')
            ->latest()
            ->whereNull('deleted_at')
            ->whereYear('start_date', '=', $year)
            ->where('location', $this->getStationCode())
            ->orderBy('start_date', 'desc')
            ->paginate();

        foreach ($events as $event) {
            $event->image = $this->verifyPhoto($event->image, 'schools');
            $event['school_name'] = $event->School->name;
            $event['slug_title'] = Str::slug($event->title);
            $event['start_date'] = date('F d, Y', strtotime($event['start_date']));
            $event['end_date'] = date('F d, Y', strtotime($event['end_date']));
        }

        return response()->json([
            'events' => $events,
        ]);
    }

    public function view($id)
    {
        $event = Gimmick::with('School')
            ->findOrFail($id);

        $event->image = $this->verifyPhoto($event->image, 'schools');
        $event['school_name'] = $event->School->school_name;
        $event['start_date'] = date('F d, Y', strtotime($event['start_date']));
        $event['end_date'] = date('F d, Y', strtotime($event['end_date']));

        return response()->json([
            'event' => $event
        ]);
    }
}
