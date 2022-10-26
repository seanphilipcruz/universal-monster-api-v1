<?php

namespace App\Http\Controllers\Website\Content\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::with('Employee')->orderBy('level')->get();

        return response()->json([
            'designations' => $designations
        ]);
    }

    public function create()
    {
        // Obsolete
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'min:2|required',
            'level' => 'numeric|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $designation = new Designation($request->all());
        $designation->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Designation successfully created!'
        ], 201);
    }

    public function show($id)
    {
        try {
            $designation = Designation::with('Employee')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred! ' . $exception->getMessage()
            ], 404);
        }

        return response()->json([
            'designation' => $designation
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $designation = Designation::with('Employee')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred! ' . $exception->getMessage()
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'min:2|required',
            'level' => 'numeric|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $designation->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Designation has been successfully updated!'
        ]);
    }

    public function destroy($id)
    {
        try {
            $designation = Designation::with('Employee')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred! ' . $exception->getMessage()
            ], 404);
        }

        $designation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Designation has been deleted successfully!'
        ]);
    }
}
