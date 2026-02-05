<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->user_id;

        return response()->json([
            'notifications' => Notification::forUser($id)->latest()->get(),
            'unread_count' => Notification::forUser($id)->unread()->count(),
            'user_id' => $id
        ]);
    }

    public function read(Request $request, $id)
    {
        $notification = Notification::forUser($request->user_id)
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }

    public function readAll(Request $request)
    {
        Notification::forUser($request->user_id)
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }

    public function unreadAll(Request $request)
    {
        Notification::forUser($request->user_id)
            ->read()
            ->update(['read_at' => null]);

        return response()->json(['message' => 'All notifications marked as unread']);
    }
}
