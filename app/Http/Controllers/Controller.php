<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use App\Models\UserLogs;
use App\Traits\SystemFunctions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Controller extends BaseController
{
    // Added System Functions
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, SystemFunctions;

    // Shorthand for my function returns
    public function json($status, $message, $status_code = 200) {
        return response()->json([
            'status' => $status,
            'message' => $message
        ], $status_code);
    }

    public function log($action, $employee_id) {
        $logs = new UserLogs([
            'user_id' => Auth::id(),
            'action' => $action,
            'employee_id' => $employee_id,
            'location' => $this->getStationCode()
        ]);

        $logs->save();
    }
}
