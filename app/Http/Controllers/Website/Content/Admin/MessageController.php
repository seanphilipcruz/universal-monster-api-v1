<?php

namespace App\Http\Controllers\Website\Content\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Throwable;

class MessageController extends Controller
{
    public function index()
    {
        $message = Message::orderBy('created_at', 'desc')->get();

        return response()->json([
            'messages' => $message
        ]);
    }

    public function show($id)
    {
        try {
            $message = Message::findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 400);
        }

        if ($message['is_seen'] == 0) {
            $this->update($id, 1);
        }

        return response()->json([
            'message' => $message
        ]);
    }

    public function update($id, $is_seen = 0)
    {
        try {
            $message = Message::findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        $message['is_seen'] = $is_seen;
        $message->save();
    }

    public function destroy($id)
    {
        try {
            $message = Message::findOrFail($id);
        } catch (Throwable $exception) {
            return $this->json('error', $exception->getMessage(), 404);
        }

        $message->delete();

        return response()->json([
            'status' => 'error',
            'message' => __('responses.success_deleted', ['Model' => 'Message'])
        ]);
    }
}
