<?php

namespace App\Http\Controllers\Website\Content\Education\Scholar;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Scholar;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BatchController extends Controller
{
    use SystemFunctions;

    public function index() {
        $batches = Batch::with('Student')
            ->whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->orderBy('number')
            ->get();

        $scholars = Scholar::with('Batch')->get();

        $data = [
            'batches' => $batches,
            'scholars' => $scholars
        ];

        return response()->json($data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'semester' => 'required',
            'number' => 'required',
            'start_year' => 'required',
            'end_year' => 'required',
            'description' => 'required',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $request['location'] = $this->getStationCode();

        $batch = new Batch($request->all());
        $batch->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Scholar Batch'])
        ]);
    }

    public function show($id)
    {
        try {
            $batch = Batch::with('Student', 'Image', 'Sponsor')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'batch' => $batch
        ]);
    }

    public function update($id, Request $request) {
        try {
            $batch = Batch::with('Student', 'Image', 'Sponsor')->findOrFail($id);

            $batch->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Scholar Batch'])
        ]);
    }

    public function destroy($id) {
        try {
            $batch = Batch::with('Student', 'Image', 'Sponsor')->findOrFail($id);

            $batch->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Scholar Batch'])
        ]);
    }
}
