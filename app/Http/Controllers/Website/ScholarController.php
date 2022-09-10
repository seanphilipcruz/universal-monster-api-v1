<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Illuminate\Http\Request;

class ScholarController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;

    public function index()
    {
        $scholarBatches = Batch::with('Student', 'Sponsor')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderByDesc('number')
            ->get();

        $batch = Batch::with('Student', 'Sponsor')
            ->whereNull('deleted_at')
            ->latest()
            ->get()
            ->first();

        $batch['image'] = $this->verifyPhoto($batch['image'], 'scholarBatch', true);

        foreach ($scholarBatches as $batch_number) {
            $batch_number['image'] = $this->verifyPhoto($batch_number['image'], 'scholarBatch', true);
        }

        return response()->json([
            'scholars' => $batch,
            'scholar_batches' => $scholarBatches
        ]);
    }

    public function view($batch_number) {
        $scholarBatches = Batch::with('Student', 'Sponsor')
            ->whereNull('deleted_at')
            ->where('location', $this->getStationCode())
            ->orderByDesc('number')
            ->get();

        $batch_id = Batch::with('Student')->firstWhere('number', $batch_number)->id;

        $batch = Batch::with('Student', 'Sponsor')->findOrFail($batch_id);

        $batch['image'] = $this->verifyPhoto($batch['image'], 'scholarBatch', true);

        foreach ($scholarBatches as $batch_number) {
            $batch_number['image'] = $this->verifyPhoto($batch_number['image'], 'scholarBatch', true);
        }

        return response()->json([
            'scholars' => $batch,
            'scholar_batches' => $scholarBatches
        ]);
    }
}
