<?php

namespace App\Http\Controllers\Website\Content\Promo;

use App\Http\Controllers\Controller;
use App\Models\Contest;
use App\Traits\AssetProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class GiveawayController extends Controller
{
    use AssetProcessors, SystemFunctions;

    public function index()
    {
        $giveaways = Contest::with('Contestant')
            ->whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->get();

        return response()->json([
            'giveaways' => $giveaways
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'name' => 'required',
            'description' => 'required',
            'type' => 'required',
            'is_restricted' => 'required',
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $contest = new Contest($request->all());
        $contest->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Contest'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $contest = Contest::with('Contestant')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'contest' => $contest
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $contest = Contest::with('Contestant')->findOrFail($id);

            $contest->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Contest'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $contest = Contest::with('Contestant')->findOrFail($id);

            $contest->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Contest'])
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

        $path = 'images/giveaways';
        $directory = 'giveaways';

        $image_name = $this->storePhoto($request, $path, $directory);

        if ($request['request_type'] == 'store') {
            return response()->json([
                'status' => 'success',
                'file_name' => $image_name
            ], 201);
        }

        $id = $request['contest_id'];
        $contest = Contest::findOrFail($id);
        $contest['image'] = $image_name;
        $contest->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_image_update', ['Model' => 'Contest'])
        ]);
    }
}
