<?php

namespace App\Http\Controllers\Website\Content\Programs;

use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class ShowController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $shows = Show::with('Jock')
            ->where('location', '=', $this->getStationCode())
            ->orderBy('title')
            ->get();

        return response()->json([
            'shows' => $shows
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'front_description' => 'required',
            'description' =>'required',
            'is_special' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['slug_string'] = Str::studly($request['title']);
        $request['location'] = $this->getStationCode();

        $show = new Show($request->all());
        $show->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Show'])
        ]);
    }

    public function show($id)
    {
        try {
            $show = Show::with('Jock', 'Timeslot', 'Link', 'Award', 'Photo')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'show' => $show
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $show = Show::with('Jock', 'Timeslot', 'Link', 'Award', 'Photo')->findOrFail($id);

            $show->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'status',
            'message' => __('responses.success_updated', ['Model' => 'Show'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $show = Show::with('Jock', 'Timeslot', 'Link', 'Award', 'Photo')->findOrFail($id);

            $show->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Show'])
        ]);
    }

    public function upload_image(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_type' => 'required',
            'image' => ['mimes:jpg,jpeg,png,webp', 'file'],
            'image_type' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $path = 'images/shows';
        $directory = 'shows';

        $image_name = $this->storePhoto($request, $path, $directory, true);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['show_id'];
        $show = Show::with('Jock')->findOrFail($id);

        if ($request['image_type'] == 'icon') {
            $show['icon'] = $image_name;
            $show->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.show_icon_saved', ['show_name' => ucfirst($show->title)])
            ]);
        } else if ($request['image_type'] == 'header') {
            $show['header_image'] = $image_name;
            $show->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.show_header_saved', ['show_name' => ucfirst($show->title)])
            ]);
        } else if ($request['image_type'] == 'background') {
            $show['background_image'] = $image_name;
            $show->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.show_background_saved', ['show_name' => ucfirst($show->title)])
            ]);
        }

        return response()->json([
            'status' => 'unknown',
            'message' => __('responses.unknown_request')
        ], 302);
    }
}
