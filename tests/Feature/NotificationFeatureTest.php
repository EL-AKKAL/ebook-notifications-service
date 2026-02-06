<?php

use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

it('returns paginated notifications for a user', function () {
    seedNotificationsForUsers();

    $response = getJson('/api/notifications', authHeaders(1));

    $response->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'title',
                    'message',
                    'is_read',
                    'type_object'
                ]
            ],
            'meta' => ['next_cursor'],
        ]);
});

it('marks a notification as read', function () {
    $notification = Notification::factory()->create(['user_id' => 1, 'read_at' => null]);

    $response = postJson("/api/notifications/{$notification->id}/read", [], authHeaders(1));

    $response->assertStatus(200)
        ->assertJson(['message' => 'Notification marked as read']);

    expect($notification->fresh()->read_at)->not()->toBeNull();
});


it('marks all notifications as read', function () {
    Notification::factory()->count(3)->create(['user_id' => 1, 'read_at' => null]);

    postJson("/api/notifications/read-all", [], authHeaders(1))
        ->assertStatus(200)
        ->assertJson(['message' => 'All notifications marked as read']);

    expect(Notification::forUser(1)
        ->unread()
        ->count())
        ->toBe(0);
});

it('throws 401, if no token provided', function () {

    seedNotificationsForUsers();

    $randomNotification = Notification::firstOrFail();

    $links = [
        "/api/notifications/read-all",
        "/api/notifications/{$randomNotification->id}/read",
        "/api/notifications/unread-all",
    ];

    foreach ($links as $link) {
        postJson(
            $link,
            [],
            authHeaders()
        )->assertUnauthorized();
    }

    getJson(
        '/api/notifications',
        authHeaders()
    )->assertUnauthorized();
});

it('prevents reading - unreading other users notifications', function () {
    $notification = Notification::factory()->create(['user_id' => 2]);

    postJson(
        "/api/notifications/{$notification->id}/read",
        [],
        authHeaders(1)
    )->assertStatus(404);
});
