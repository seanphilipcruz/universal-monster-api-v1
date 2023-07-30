<?php

namespace App\Http\Controllers\Website\Content\Charts;

use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Models\Song;
use App\Traits\ChartFunctions;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ChartController extends Controller
{
    use SystemFunctions, ChartFunctions;

    public function index()
    {
        $latest_date = $this->getLatestChartDate();
        $location = $this->getStationCode();

        $chart = Chart::with('Song.Album.Artist')
            ->where('dated' , $latest_date)
            ->whereNull('deleted_at')
            ->where('local', 0)
            ->where('position', '>', 0)
            ->where('location', $location)
            ->orderBy('position')
            ->get();

        $data = [
            'chart_date' => $latest_date,
            'charts' => $chart
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'song_id' => 'required',
            'position' => 'required',
            'dated' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['last_position'] = 0;
        $request['re_entry'] = 0;
        $request['is_dropped'] = 0;
        $request['location'] = $this->getStationCode();

        $chart = new Chart($request->all());
        $chart->save();

        $song_id = $request['song_id'];
        $song = Song::with('Chart')->findOrFail($song_id);
        $song['is_charted'] = 1;
        $song->save();

        if ($request['local']) {
            return response()->json([
                'status' => 'success',
                'message' => __('responses.new_chart_success')
            ], 201);
        }
}

    public function switch_charts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required',
            'dated' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $action = $request['action'];
        $chart_date = $request['dated'];

        // For switching what type of chart should be shown
        if ($action == 'draft') {
            $charts = Chart::with('Song')
                ->where('dated', $chart_date)
                ->whereNull('deleted_at')
                ->where('local', '=', 1)
                ->where('is_posted', '=', 0)
                ->where('location', '=', $this->getStationCode())
                ->where('position', '>', 0)
                ->orderBy('position')
                ->get();

            return response()->json([
                'charts' => $charts
            ]);
        }

        // By default, show what is the latest chart
        $charts = Chart::with('Song')
            ->where('dated', $chart_date)
            ->whereNull('deleted_at')
            ->where('local', '=', 1)
            ->where('is_posted', '=', 1)
            ->where('location', '=', $this->getStationCode())
            ->where('position', '>', 0)
            ->orderBy('position')
            ->get();

        return response()->json([
            'charts' => $charts
        ]);
    }

    public function show($id) {
        $chart = Chart::with('Song.Album.Artist')
            ->findOrFail($id);

        return response()->json([
            'chart' => $chart
        ]);
    }

    public function update($id, Request $request)
    {
        $action = $request['action'];
        $chart_date = $request['dated'];

        try {
            $chart = Chart::with('Song')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        if ($action == 'post') {
            Chart::with('Song')
                ->where('dated', $chart_date)
                ->whereNull('deleted_at')
                ->where('local', '=', 1)
                ->where('is_posted', '=', 0)
                ->where('location', '=', $this->getStationCode())
                ->update(['is_posted' => 1]);

            return response()->json([
                'status' => 'success',
                'message' => __('responses.chart_update_posted', ['Date' => date('M d, Y', strtotime($chart_date))])
            ]);
        }

        $chart->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => __('responses.chart_update_success')
        ]);
    }

    public function destroy($id)
    {
        try {
            $chart = Chart::with('Song')->findOrFail($id);

            $chart->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Chart'])
        ]);
    }
}
