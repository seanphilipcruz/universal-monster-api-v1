<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Chart;
use App\Traits\ChartFunctions;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    use SystemFunctions;
    use ChartFunctions;
    use MediaProcessors;

    public function index()
    {
        $charts = Chart::with('Song.Album.Artist')->where('dated', $this->getLatestChartDate())
            ->where('daily', 0)
            ->where('is_posted', 1)
            ->where('location', $this->getStationCode())
            ->orderBy('position')
            ->get();

        foreach ($charts as $chart) {
            $chart->Song->Album->image = $this->verifyPhoto($chart->Song->Album->image, 'albums');
            $chart->Song->Album->image = $this->getAssetUrl('albums') . $chart->Song->Album->image;
        }

        return response()->json([
            'charts' => $charts
        ]);
    }

    public function vote(Request $request) {
        $chart_id = $request->chart_id;

        $chart = Chart::with('Song.Album.Artist')->findOrFail($chart_id);

        $chart->voted_at = date('Y-m-d');
        ++$chart->phone_votes;
        $chart->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Vote Sent!'
        ]);
    }
}
