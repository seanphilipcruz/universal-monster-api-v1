<?php

namespace App\Http\Controllers\Website\Content\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bugs;
use App\Traits\AssetProcessors;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BugController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        // List only the bugs that is not yet resolved.
        $bugs = Bugs::all()->where('is_resolved', '=', 0);

        return response()->json([
            'bugs' => $bugs
        ]);
    }

    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required'
        ]);

        if ($validation->fails()) {
            return $this->json('error', $validation->errors(), 400);
        }

        $bug = new Bugs($request->all());
        $bug->save();

        return $this->json('success', __('responses.success_created', ['Model' => 'Bug']), 201);
    }

    public function show($id)
    {
        try {
            $bug = Bugs::findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'bug' => $bug
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $bug = Bugs::findOrFail($id);

            $bug->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        if ($request['is_resolved'] == 1) {
            return response()->json([
                'status' => 'success',
                'message' => __('responses.bug_opened')
            ]);
        } else if ($request['is_resolved'] == 0) {
            return response()->json([
                'status' => 'success',
                'message' => __('responses.bug_closed')
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'bug'])
        ]);
    }

    public function destroy($id)
    {
        // Never use this function, bugs should not be deleted.
        try {
            $bug = Bugs::findOrFail($id);

            $bug->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'bug'])
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

        $path = 'images/bugs';
        $directory = 'bugs';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['bug_id'];

        try {
            $bug = Bugs::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $bug['image'] = $image_name;
        $bug->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_upload', ['Model' => 'Bug'])
        ]);
    }
}
