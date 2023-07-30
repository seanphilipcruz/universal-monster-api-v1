<?php

namespace App\Http\Controllers\Website\Content\Programs;

use App\Http\Controllers\Controller;
use App\Models\Jock;
use App\Models\Show;
use App\Models\Timeslot;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class TimeslotController extends Controller
{
    use SystemFunctions;

    public function index()
    {
        $day = date('l');

        $jocks = Jock::with('Employee')
            ->whereHas('Employee', function(Builder $builder) {
                $builder->where('location', '=', $this->getStationCode());
            })->whereNull('deleted_at')
            ->where('is_active', '=', 1)
            ->orderBy('name')
            ->get();

        $shows = Show::with('Jock')
            ->whereNull('deleted_at')
            ->where('is_active', '=', 1)
            ->orderBy('title')
            ->get();

        $timeslots = Timeslot::with('Show', 'Jock')
            ->whereNull('deleted_at')
            ->where('day', $day)
            ->where('location', '=', $this->getStationCode())
            ->orderBy('start')
            ->get();

        $data = [
            'jocks' => $jocks,
            'shows' => $shows,
            'timeslots' => $timeslots
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required',
            'start' => 'required',
            'end' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['location'] = $this->getStationCode();

        $timeslot = new Timeslot($request->all());
        $timeslot->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Timeslot'])
        ], 201);
     }

    public function show($id)
    {
        try {
            $timeslot = Timeslot::with('Show', 'Jock')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'timeslot' => $timeslot
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $timeslot = Timeslot::with('Show', 'Jock')->findOrFail($id);

            $timeslot->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }


        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Timeslot'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $timeslot = Timeslot::with('Show', 'Jock')->findOrFail($id);

            $timeslot->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Timeslot'])
        ]);
    }
}
