<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user_id;

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'user_id' => $userId
        ]);
    }

    public function read(Request $request, $id)
    {
        $userId = $request->user_id;

        $notification = Notification::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function readAll(Request $request)
    {
        $userId = $request->user_id;

        Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    public function unreadAll(Request $request)
    {
        $userId = $request->user_id;

        Notification::where('user_id', $userId)
            ->update([
                'read_at' => null,
            ]);

        return response()->json([
            'message' => 'All notifications marked as unread',
        ]);
    }
}
