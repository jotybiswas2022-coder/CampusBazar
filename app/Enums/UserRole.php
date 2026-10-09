<?php

namespace App\Enums;

class UserRole
{
    public const STUDENT = 'student';
    public const ADMIN = 'admin';

    public static function all(): array
    {
        return [self::STUDENT, self::ADMIN];
    }

    public static function label(string $role): string
    {
        return match ($role) {
            self::ADMIN => 'Administrator',
            default => 'Registered Student',
        };
    }
}
