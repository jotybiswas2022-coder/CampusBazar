<?php

namespace App\Enums;

class PaymentStatus
{
    public const UNPAID = 'unpaid';
    public const PAID = 'paid';
    public const FAILED = 'failed';

    public static function all(): array
    {
        return [self::UNPAID, self::PAID, self::FAILED];
    }

    public static function label(string $status): string
    {
        return match ($status) {
            self::PAID => 'Paid',
            self::FAILED => 'Not Collected',
            default => 'Unpaid',
        };
    }
}
