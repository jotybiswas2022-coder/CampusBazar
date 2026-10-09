<?php

namespace App\Enums;

class OrderStatus
{
    public const PENDING_CONFIRMATION = 'pending_confirmation';
    public const CONFIRMED = 'confirmed';
    public const PROCESSING = 'processing';
    public const SHIPPED = 'shipped';
    public const OUT_FOR_DELIVERY = 'out_for_delivery';
    public const DELIVERED = 'delivered';
    public const CANCELLED = 'cancelled';
    public const RETURNED = 'returned';

    public static function all(): array
    {
        return [
            self::PENDING_CONFIRMATION,
            self::CONFIRMED,
            self::PROCESSING,
            self::SHIPPED,
            self::OUT_FOR_DELIVERY,
            self::DELIVERED,
            self::CANCELLED,
            self::RETURNED,
        ];
    }

    public static function label(string $status): string
    {
        return match ($status) {
            self::PENDING_CONFIRMATION => 'Pending Confirmation',
            self::CONFIRMED => 'Confirmed',
            self::PROCESSING => 'Processing',
            self::SHIPPED => 'Shipped',
            self::OUT_FOR_DELIVERY => 'Out for Delivery',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
            self::RETURNED => 'Returned',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    /**
     * Allowed transitions from a given status.
     *
     * @return array<int, string>
     */
    public static function transitions(string $from): array
    {
        return match ($from) {
            self::PENDING_CONFIRMATION => [self::CONFIRMED, self::CANCELLED],
            self::CONFIRMED => [self::PROCESSING, self::CANCELLED],
            self::PROCESSING => [self::SHIPPED, self::CANCELLED],
            self::SHIPPED => [self::OUT_FOR_DELIVERY, self::RETURNED],
            self::OUT_FOR_DELIVERY => [self::DELIVERED, self::RETURNED],
            self::DELIVERED => [self::RETURNED],
            default => [],
        };
    }

    public static function canTransition(string $from, string $to): bool
    {
        return in_array($to, self::transitions($from), true);
    }

    public static function isFinal(string $status): bool
    {
        return in_array($status, [self::CANCELLED, self::RETURNED], true);
    }
}
