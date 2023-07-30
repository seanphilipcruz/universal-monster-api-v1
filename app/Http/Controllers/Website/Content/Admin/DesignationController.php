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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'min:2|required',
            'level' => 'numeric|required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $designation = new Designation($request->all());
        $designation->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Designation'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $designation = Designation::with('Employee')->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'designation' => $designation
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $designation = Designation::with('Employee')->findOrFail($id);

            $designation->update($request->all());
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Designation'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $designation = Designation::with('Employee')->findOrFail($id);

            $designation->delete();
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Designation'])
        ]);
    }
}
