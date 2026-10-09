<?php

namespace App\Enums;

class ProductStatus
{
    public const PENDING_APPROVAL = 'pending_approval';
    public const ACTIVE = 'active';
    public const RESERVED = 'reserved';
    public const SOLD = 'sold';
    public const REJECTED = 'rejected';
    public const REMOVED = 'removed';

    public static function all(): array
    {
        return [
            self::PENDING_APPROVAL,
            self::ACTIVE,
            self::RESERVED,
            self::SOLD,
            self::REJECTED,
            self::REMOVED,
        ];
    }

    public static function label(string $status): string
    {
        return match ($status) {
            self::ACTIVE => 'Active',
            self::RESERVED => 'Reserved',
            self::SOLD => 'Sold',
            self::REJECTED => 'Rejected',
            self::REMOVED => 'Removed',
            default => 'Pending Approval',
        };
    }
}
