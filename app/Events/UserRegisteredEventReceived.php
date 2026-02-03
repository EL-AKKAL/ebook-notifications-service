<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredEventReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $id,
        public string $email,
        public string $name
    ) {}
}
