<?php

namespace App\Http\Controllers\Website\Content\Programs;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\Show;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PodcastController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $podcasts = Podcast::with('Show')
            ->orderBy('date', 'desc')
            ->get();
        $shows = Show::with('Podcast')
            ->orderBy('title')
            ->get();

        $data = [
            'podcasts' => $podcasts,
            'shows' => $shows
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'show_id' => 'required',
            'episode' => 'required',
            'date' => 'required',
            'link' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['location'] = $this->getStationCode();

        $podcast = new Podcast($request->all());
        $podcast->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Podcast'])
        ]);
    }

    public function show($id)
    {
        try {
            $podcast = Podcast::with('Show')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'podcast' => $podcast
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $podcast = Podcast::with('Show')->findOrFail($id);

            $podcast->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Podcast'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $podcast = Podcast::with('Show')->findOrFail($id);

            $podcast->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Podcast'])
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

        $path = 'images/podcasts';
        $directory = 'podcasts';

        $image_name = $this->storePhoto($request, $path, $directory, true);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['podcast_id'];
        $podcast = Podcast::with('Show')->findOrFail($id);
        $podcast['image'] = $image_name;
        $podcast->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'Podcast'])
        ]);
    }
}
