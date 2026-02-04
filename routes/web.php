<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        'message' => 'Ebook Notifications Service is running.',
        'api routes' => [
            '/notifications' => 'Get all notifications',
            '/notifications/{id}/read' => 'Mark a notification as read',
            '/notifications/read-all' => 'Mark all notifications as read',
        ],
        'pipeline' => 'CI/CD pipeline is set up for automated deployments.',
    ]);
});
