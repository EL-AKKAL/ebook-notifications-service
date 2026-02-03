<?php

namespace App\Listeners;

use App\Enums\NotificationTypeEnum;
use App\Events\NotificationCreated;
use App\Events\UserRegisteredEventReceived;
use App\Models\Notification;

class SendWelcomeNotification
{
    public function __construct() {}

    public function handle(UserRegisteredEventReceived $event): void
    {
        $notification = Notification::create([
            'user_id' => $event->id,
            'user_name' => $event->name,
            'user_email' => $event->email,
            'type' => NotificationTypeEnum::WELCOME->value,
            'title' => "{$event->name}, You're in",
            'message' => 'Welcome to Ebook! Excited to have you here.'
        ]);

        event(new NotificationCreated($notification, $event->id));
    }
}
