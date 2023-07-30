<?php

namespace App\Http\Controllers\Website\Content\Education\Scholar;

use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class SponsorController extends Controller
{

    public function index()
    {
        $sponsors = Sponsor::with('Batch')
            ->whereNull('deleted_at')
            ->get();

        return response()->json([
            'sponsors' => $sponsors
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'remarks' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $sponsor = new Sponsor($request->all());
        $sponsor->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Sponsor'])
        ]);
    }

    public function show($id)
    {
        try {
            $sponsor = Sponsor::with('Student')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'sponsor' => $sponsor
        ]);
    }
    public function update($id, Request $request)
    {
        try {
            $sponsor = Sponsor::with('Student')->findOrFail($id);

            $sponsor->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Sponsor'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $sponsor = Sponsor::with('Student')->findOrFail($id);

            $sponsor->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Sponsor'])
        ]);
    }
}
