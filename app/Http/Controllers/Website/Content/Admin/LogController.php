<?php

namespace App\Http\Controllers\Website\Content\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserLogs;
use App\Traits\SystemFunctions;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LogController extends Controller
{
    use SystemFunctions;

    public function index()
    {
        $user_logs = UserLogs::with('User.Employee')->get();

        return response()->json([
            'logs' => $user_logs
        ]);
    }

    public function show($id)
    {
        try {
            $userLogs = UserLogs::with('User')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        return response()->json([
            'log' => $userLogs
        ]);
    }
}
