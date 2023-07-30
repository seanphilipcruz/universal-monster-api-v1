<?php

namespace App\Http\Controllers\Website\Content\Promo;

use App\Http\Controllers\Controller;
use App\Models\Contestant;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ContestantController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $contestants = Contestant::with('Contest')
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'contestants' => $contestants
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'birthday' => 'required|date',
            'city' => 'required|min:10',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['password'] = Hash::make($request['password']);

        $contestant = new Contestant($request->all());
        $contestant->save();

        return $this->json('success', __('responses.success_created', ['Model' => 'Contestant']), 201);
    }

    public function show($id)
    {
        try {
            $contestant = Contestant::with('Contest')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'contestant' => $contestant
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $contestant = Contestant::with('Contest')->findOrFail($id);

            if ($request->has('password')) {
                $validator = Validator::make($request->all(), [
                    'password' => 'required|confirmed',
                    'confirm_password' => 'required'
                ]);

                if ($validator->fails()) {
                    return $this->json('error', $validator->errors()->all(), 400);
                }

                $password_match = Hash::check($request['password'], $contestant['password']);

                if ($password_match) {
                    $contestant->update($request->only('password'));

                    return $this->json('success', __('responses.change_password_success'));
                } else {
                    return $this->json('error', __('responses.change_password_failed'), 400);
                }
            }

            $contestant->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Contestant'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $contestant = Contestant::with('Contest')->findOrFail($id);

            $contestant->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Contestant'])
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

        $path = 'images/contestants';
        $directory = 'contestants';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['contestant_id'];
        $contestant = Contestant::with('Contest')->findOrFail($id);
        $contestant['image'] = $image_name;
        $contestant->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'Contestant'])
        ]);
    }
}
