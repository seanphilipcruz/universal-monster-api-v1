<?php

namespace App\Http\Controllers\Website\Content\Education;

use App\Http\Controllers\Controller;
use App\Models\Gimmick;
use App\Models\School;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class GimikboardController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $events = Gimmick::with('School')
            ->orderBy('created_at', 'desc')
            ->where('location', '=', $this->getStationCode())
            ->get();


        $schools = School::with('Gimikboard')
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        $data = [
            'events' => $events,
            'schools' => $schools
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'start_date' => 'required',
            'description' => 'required',
            'school_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        if ($request['end_date']) {
            if ($request['end_date'] < $request['start_date']) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('responses.date_error_end_not_great_than_start')
                ], 400);
            }
        }

        $event = new Gimmick($request->all());
        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Gimikboard'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $gimmick = Gimmick::with('School')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'event' => $gimmick
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $gimmick = Gimmick::with('School')->findOrFail($id);

            $gimmick->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Gimikboard'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $gimmick = Gimmick::with('School')->findOrFail($id);

            $gimmick->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Gimikboard'])
        ]);
    }

    public function upload_image(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required',
            'image' => ['mimes:jpg,jpeg,png,webp', 'file']
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $path = 'images/schools';
        $directory = 'schools';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['event_id'];
        $event = Gimmick::with('School')->findOrFail($id);
        $event['image'] = $image_name;
        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'Gimikboard'])
        ]);
    }
}
