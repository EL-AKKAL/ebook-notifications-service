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
        $options = config('queue.connections.rabbitmq.options');

        $connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'] ?? env('RABBITMQ_PASSWORD', 'guest'),
            $config['vhost'],
            false,
            $options['login_method'] ?? 'AMQPLAIN',
            null,
            'en_US',
            3.0,
            $options['read_write_timeout'] ?? 10,
            null,
            false,
            $options['heartbeat'] ?? 60
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
