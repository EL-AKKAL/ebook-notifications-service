<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateNotifications extends Command
{
    protected $signature = 'app:generate-notifications {userID?} {count?}';

    protected $description = 'create notifications for a given user.';

    public function handle()
    {
        try {
            $userID = $this->argument('userID') ?? $this->ask('Enter user ID (default is 1)', '1');
            $count = $this->argument('count') ?? $this->ask('How many notifications to create ? (default is 10)', '10');

            $this->info("Creating {$count} notifications for user #{$userID} ...");

            DB::beginTransaction();

            $this->withProgressBar(range(1, (int) $count), function () use ($userID) {
                Notification::factory()->create([
                    'user_id' => $userID,
                ]);
            });

            DB::commit();

            $this->info('creating notifications completed successfully.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error creating notifications: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
