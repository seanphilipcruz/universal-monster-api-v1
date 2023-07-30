<?php

namespace App\Http\Controllers\Website\Content\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('Employee')->get();

        return response()->json([
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        // Refer to registration controller
    }

    public function show($id)
    {
        try {
            $user = User::with('Employee')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    // For updating the password.
    // Todo: Add last login to automatically disable user access.
    public function update($id, Request $request)
    {
        try {
            $user = User::with('Employee')->findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        $password_verify = Hash::check($request['password'], $user['password']);

        if (!$password_verify) {
            return response()->json([
                'status' => 'error',
                'message' => __('responses.change_password_failed')
            ], 400);
        }

        $user->fill($request->only('password'))->save();

        return response()->json([
            'status' => 'error',
            'message' => __('responses.change_password_success')
        ]);
    }

    public function destroy(User $user)
    {
        // No deleting of users
    }
}
