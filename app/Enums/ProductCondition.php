<?php

namespace App\Enums;

class ProductCondition
{
    public const NEW = 'new';
    public const LIKE_NEW = 'like_new';
    public const GOOD = 'good';
    public const FAIR = 'fair';

    public static function all(): array
    {
        return [self::NEW, self::LIKE_NEW, self::GOOD, self::FAIR];
    }

    public static function label(string $condition): string
    {
        return match ($condition) {
            self::NEW => 'New',
            self::LIKE_NEW => 'Like New',
            self::GOOD => 'Good',
            self::FAIR => 'Fair',
            default => ucfirst(str_replace('_', ' ', $condition)),
        };
    }
}
