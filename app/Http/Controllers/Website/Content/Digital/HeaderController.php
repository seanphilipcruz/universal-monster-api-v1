<?php

namespace App\Http\Controllers\Website\Content\Digital;

use App\Http\Controllers\Controller;
use App\Models\Header;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class HeaderController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $headers = Header::whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->orderBy('number')
            ->get();

        return response()->json([
            'headers' => $headers
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $headers_count = Header::whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->count();

        if ($headers_count == 0) {
            $request['number'] = 0;
        } else {
            $recent_header_count = Header::whereNull('deleted_at')
                ->where('location', '=', $this->getStationCode())
                ->max('number');

            $request['number'] = $recent_header_count + 1;
        }

        $header = new Header($request->all());
        $header->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Header'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $header = Header::findorFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'header' => $header
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $header = Header::findorFail($id);

            $header->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Header'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $header = Header::findorFail($id);

            $header->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Header'])
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

        $path = 'images/headers';
        $directory = 'headers';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' =>  $image_name
            ], 201);
        }

        $id = $request['header_id'];

        try {
            $header = Header::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        $header['image'] = $image_name;
        $header->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_upload', ['Model' => 'Header'])
        ]);
    }
}
