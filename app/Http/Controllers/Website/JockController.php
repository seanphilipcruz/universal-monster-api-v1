<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Jock;
use App\Models\StudentJockBatch;
use App\Traits\MediaProcessors;
use App\Traits\SystemFunctions;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class JockController extends Controller
{
    use SystemFunctions;
    use MediaProcessors;

    public function index(Request $request)
    {
        $day = Carbon::now()->format('l');
        $date = date('F d, Y');
        $getTime = Carbon::now('Asia/Manila');
        $time = date('H:i', strtotime($getTime));

        // old sorting where it was sorted by jock id.
        $jocks = Employee::with('Jock.Show', 'Jock.Fact', 'Jock.Image', 'Jock.Link', 'Jock.Award')->whereHas('Jock', function(Builder $query) {
            $query->where('is_active', '=', 1);
        })->has('Jock.Show')
            ->where('location', $this->getStationCode())
            ->whereNull('deleted_at')
            ->get();

        foreach ($jocks as $employee) {
            foreach ($employee->Jock as $jock) {
                $jock->profile_image = $this->verifyPhoto($jock->profile_image, 'jocks');
                $jock->background_image = $this->verifyPhoto($jock->background_image, 'jocks');
                $jock->main_image = $this->verifyPhoto($jock->main_image, 'jocks');
            }
        }

        // sort jocks by shows
        /*$jocks = Show::with('Jock.Employee')->whereHas('Jock.Employee', function(Builder $query) {
            $query->where('is_active', '=', 1);
        })->whereNull('deleted_at')
            ->where('is_active', '=', 1)
            ->where('is_special', '=', 0)
            ->where('location', $this->getStationCode())
            ->get();*/

        return response()->json([
            'jocks' => $jocks
        ]);
    }

    public function view($slugString) {
        $jock = Jock::with('Show', 'Fact', 'Link', 'Award')
            ->where('slug_string', '=', $slugString)
            ->whereHas('Image', function(Builder $builder) {
                $builder->whereNull('deleted_at');
            })
            ->where('is_active', '=', 1)
            ->first();

        $jock['profile_image'] = $this->verifyPhoto($jock['profile_image'], 'jocks');
        $jock['background_image'] = $this->verifyPhoto($jock['background_image'], 'jocks');
        $jock['main_image'] = $this->verifyPhoto($jock['main_image'], 'jocks');

        foreach ($jock->Image as $image) {
            $image->file = $this->verifyPhoto($image->file, 'jocks');
        }

        foreach ($jock->Show as $show) {
            $show['icon'] = $this->verifyPhoto($show['icon'], 'shows');
            $show['header_image'] = $this->verifyPhoto($show['header_image'], 'shows');
            $show['background_image'] = $this->verifyPhoto($show['background_image'], 'shows');
        }

        return response()->json([
            'jock' => $jock
        ]);
    }

    public function radio1(Request $request) {
        $batch = StudentJockBatch::with('Student.School')
            ->latest()
            ->get();

        $studentJocks = StudentJockBatch::with(['Student' => function($query) {
            $query->orderBy('position');
        }, 'Student.School'])->has('Student')
            ->latest()
            ->get()
            ->first();

        if($request['batch']) {
            $studentJocks = StudentJockBatch::with('Student.School')
                ->where('batch_number', '=', $request['batch'])
                ->get()
                ->first();
        }

        foreach ($studentJocks->Student as $student) {
            $student->image = $this->verifyPhoto($student->image, 'studentJocks');

            $student->School->image = $this->verifyPhoto($student->School->image, 'schools');
        }

        return response()->json([
            'batches' => $batch,
            'radio1' => $studentJocks
        ]);
    }
}
