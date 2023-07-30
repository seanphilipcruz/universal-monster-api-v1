<?php

namespace App\Http\Controllers\Website\Content\Digital;

use App\Http\Controllers\Controller;
use App\Models\Wallpaper;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class WallpaperController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index(Request $request)
    {
        $device_type = $request['device_type'];

        if ($device_type == 'mobile') {
            $wallpapers = Wallpaper::whereNull('deleted_at')
                ->where('location', '=', $this->getStationCode())
                ->where('device', '=', 'mobile')
                ->latest()
                ->get();

            return response()->json([
                'wallpapers' => $wallpapers
            ]);
        }

        $wallpapers = Wallpaper::whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->where('device', '=', 'web')
            ->latest()
            ->get();

        return response()->json([
            'wallpapers' => $wallpapers
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'device' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['location'] = $this->getStationCode();

        $wallpaper = new Wallpaper($request->all());
        $wallpaper->save();

        return response()->json([
            'status' =>'success',
            'message' => __('responses.success_created', ['Model' => 'Wallpaper'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $wallpaper = Wallpaper::findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'wallpaper' => $wallpaper
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $wallpaper = Wallpaper::findOrFail($id);

            $wallpaper->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }


        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Wallpaper'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $wallpaper = Wallpaper::findOrFail($id);

            $wallpaper->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Wallpaper'])
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

        $path = 'images/wallpapers';
        $directory = 'wallpapers';

        $image_name = $this->storePhoto($request, $path, $directory, true);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['wallpaper_id'];
        $wallpaper = Wallpaper::findOrFail($id);
        $wallpaper['image'] = $image_name;
        $wallpaper->save();

        return response()->json([
            'status' => 'success',
            'message' =>  __('responses.success_image_update', ['Model' => 'Wallpaper'])
        ]);
    }
}
