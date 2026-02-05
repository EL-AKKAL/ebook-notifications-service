<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case WELCOME = 'welcome';
    case INFO = 'info';
    case RECAP = 'recap';
    case ALERT = 'alert';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::WELCOME => 'Welcome',
            self::INFO => 'Info',
            self::RECAP => 'Recap',
            self::ALERT => 'Alert',
        };
    }
}
