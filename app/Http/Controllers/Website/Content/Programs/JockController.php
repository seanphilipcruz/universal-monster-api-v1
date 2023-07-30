<?php

namespace App\Http\Controllers\Website\Content\Programs;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Jock;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class JockController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $jocks = Jock::with('Employee')
            ->get();

        $employees = Employee::with('User')
            ->whereDoesntHave('Jock')
            ->where('designation_id', '=', 9)
            ->whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->where('is_active', '=', 1)
            ->get();

        $data = [
            'jocks' => $jocks,
            'employees' => $employees
        ];

        return response()->json($data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'name' => 'required',
            'profile_image' => 'required',
            'background_image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['slug_string'] = Str::slug($request['name']);
        $request['is_active'] = 1;

        $jock = new Jock($request->all());
        $jock->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Jock'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $jock = Jock::with('Show')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'jock' => $jock
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $jock = Jock::with('Show')->findOrFail($id);

            $jock->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Jock'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $jock = Jock::with('Show')->findOrFail($id);

            $jock->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Jock'])
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

        $path = 'images/jocks';
        $directory = 'jocks';

        $image_name = $this->storePhoto($request, $path, $directory, true);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['jock_id'];
        $jock = Jock::with('Image')->findOrFail($id);

        if ($request['image_type'] == 'profile') {
            $jock['profile_image'] = $image_name;
            $jock->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.jock_profile_saved', ['jock_name' => ucfirst($jock->name)])
            ]);
        } else if ($request['image_type'] == 'header') {
            $jock['background_image'] = $image_name;
            $jock->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.jock_background_saved', ['jock_name' => ucfirst($jock->name)])
            ]);
        } else if ($request['image_type'] == 'main') {
            $jock['main_image'] = $image_name;
            $jock->save();

            return response()->json([
                'status' => 'success',
                'message' => __('responses.jock_main_saved', ['jock_name' => ucfirst($jock->name)])
            ]);
        }

        return response()->json([
            'status' => 'unknown',
            'message' => __('responses.unknown_request')
        ], 302);
    }
}
