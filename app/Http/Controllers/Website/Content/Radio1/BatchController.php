<?php

namespace App\Http\Controllers\Website\Content\Radio1;

use App\Http\Controllers\Controller;
use App\Models\StudentJockBatch;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BatchController extends Controller
{
    use SystemFunctions;

    public function index()
    {
        $studentJockBatches = StudentJockBatch::with('Student')
            ->whereNull('deleted_at')
            ->where('location', '=', $this->getStationCode())
            ->get();

        return response()->json([
            'batches' => $studentJockBatches
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_number' => 'required',
            'start_year' => 'required',
            'end_year' => ['required', 'accepted_if:start_year<=end_year']
        ]);

        if ($validator->fails()) {
            return $this->json('error', $validator->errors()->all(), 400);
        }

        $batch = new StudentJockBatch($request->all());
        $batch->save();

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_created', ['Model' => 'Radio1 Batch'])
        ], 201);
    }

    public function show($id)
    {
        try {
            $studentJockBatch = StudentJockBatch::with('Student')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'batch' => $studentJockBatch
        ]);
    }

    public function update($id, Request $request)
    {
        try {
            $studentJockBatch = StudentJockBatch::with('Student')->findOrFail($id);

            $studentJockBatch->update($request->all());
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_updated', ['Model' => 'Radio1 Batch'])
        ]);
    }

    public function destroy($id)
    {
        try {
            $studentJockBatch = StudentJockBatch::with('Student')->findOrFail($id);

            $studentJockBatch->delete();
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('responses.success_deleted', ['Model' => 'Radio1 Batch'])
        ]);
    }
}
