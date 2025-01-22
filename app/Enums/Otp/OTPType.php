<?php

namespace App\Enums;

enum OTPType: string
{
    case LOGIN = 'login';
    case REGISTER = 'register';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}