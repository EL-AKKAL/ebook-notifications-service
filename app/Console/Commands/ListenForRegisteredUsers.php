<?php

namespace App\Console\Commands;

use App\Events\UserRegisteredEventReceived;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ListenForRegisteredUsers extends Command
{
    protected $signature = 'app:listen-registered-users';
    protected $description = 'Listen for registered users on rabbitmq and create a notification';

    public function handle()
    {
        $config = config('queue.connections.rabbitmq.hosts')[0];
        $connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            env('RABBITMQ_PASSWORD'),
            $config['vhost']
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            'user-events',
            false,
            true,
            false,
            false
        );

        $callback = function ($msg) {
            $data = json_decode($msg->body, true);

            try {
                event(new UserRegisteredEventReceived(
                    $data['user_id'],
                    $data['email'],
                    $data['name']
                ));
            } catch (\Throwable $e) {
                logger()->error($e->getMessage(), ['payload' => $data]);
            }
        };

        $channel->basic_consume('user-events', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
