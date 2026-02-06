<?php

namespace Database\Factories;

use App\Enums\NotificationTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(NotificationTypeEnum::all()),
            'title' => $this->faker->sentence(),
            'message' => $this->faker->sentence(),
            'read_at' => null,
            'user_name' => $this->faker->name(),
            'user_email' => $this->faker->safeEmail(),
        ];
    }
}
