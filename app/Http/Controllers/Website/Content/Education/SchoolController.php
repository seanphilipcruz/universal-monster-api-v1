<?php

namespace App\Http\Controllers\Website\Content\Education;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SchoolController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $schools = School::whereNull('deleted_at')
            ->orderBy('name')
            ->get();

        return response()->json([
            'schools' => $schools
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['location'] = $this->getStationCode();
        $request['seal'] = $request['image'];

        $school = new School($request->all());
        $school->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'School'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $school = School::findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'school' => $school
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $school = School::findOrFail($id);

            $school->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'School'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $school->deleteOrFail();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'School'])
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

        $id = $request['school_id'];
        $school = School::findOrFail($id);
        $school['seal'] = $image_name;
        $school->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'School'])
        ]);
    }
}
