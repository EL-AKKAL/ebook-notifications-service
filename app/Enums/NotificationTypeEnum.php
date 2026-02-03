<?php

namespace App\Enums;

enum NotificationTypeEnum: string
{
    case WELCOME = 'Welcome';
    case INFO = 'Info';
    case RECAP = 'Recap';
    case ALERT = 'Alert';


    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
