<?php

use App\Models\Notification;

function seedNotificationsForUsers(): void
{
    Notification::factory()->count(3)->create(['user_id' => 1]);
    Notification::factory()->count(2)->create(['user_id' => 2]);
}
