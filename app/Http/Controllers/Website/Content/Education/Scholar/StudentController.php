<?php

namespace App\Http\Controllers\Website\Content\Education\Scholar;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class StudentController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $students = Student::with('School')
            ->whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
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
            'course' => 'required',
            'year_level' => 'required',
            'scholar_type' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $student = new Student($request->all());
        $student->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Student'])
        ]);
    }

    public function show($id)
    {
        try {
            $student = Student::with('Batch')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'student' => $student
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $student = Student::with('Batch')->findOrFail($id);

            $student->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Student'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $student = Student::with('Sponsor', 'Batch')->findOrFail($id);

            $student->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Student'])
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

        $path = 'images/scholars';
        $directory = 'students';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ]);
        }

        $id = $request['student_id'];
        $student = Student::with('Batch')->findOrFail($id);
        $student['image'] = $image_name;
        $student->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'Student'])
        ]);
    }
}
