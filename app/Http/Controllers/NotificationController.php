<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user_id;

        $notifications = Notification::forUser($userId)
            ->latestFirst()
            ->get();

        $unreadCount = $notifications->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'user_id' => $userId
        ]);
    }

    public function read(Request $request, $id)
    {
        $notification = Notification::forUser($request->user_id)
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    public function readAll(Request $request)
    {
        Notification::forUser($request->user_id)
            ->unread()
            ->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }

    public function unreadAll(Request $request)
    {
        Notification::forUser($request->user_id)
            ->read()
            ->markAsUnread();

        return response()->json([
            'message' => 'All notifications marked as unread',
        ]);
    }
}
