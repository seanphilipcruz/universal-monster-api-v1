<?php

namespace App\Http\Controllers\Website\Content\Radio1;

use App\Http\Controllers\Controller;
use App\Models\StudentJock;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class JockController extends Controller
{
    use AssetProcessors;

    public function index()
    {
        $students = StudentJock::with('School')
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'students' => $students
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'position' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $nickname = $request['nickname'];

        if (empty($nickname)) {
            $request['nickname'] = $request['first_name'];
        }

        $radio1 = new StudentJock($request->all());
        $radio1->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Radio1 Student'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $studentJock = StudentJock::with('Batch', 'School')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'student' => $studentJock
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $studentJock = StudentJock::with('Batch', 'School')->findOrFail($id);

            $studentJock->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Radio1 Student'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $studentJock = StudentJock::with('Batch', 'School')->findOrFail($id);

            $studentJock->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Radio1 Student'])
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

        $path = 'images/studentJocks';
        $directory = 'studentJocks';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['radio1_id'];
        $radio1 = StudentJock::with('Batch')->findOrFail($id);
        $radio1['image'] = $image_name;
        $radio1->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'Radio1 Student'])
        ]);
    }
}
