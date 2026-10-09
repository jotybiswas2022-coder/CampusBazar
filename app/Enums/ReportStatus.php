<?php

namespace App\Enums;

class ReportStatus
{
    public const OPEN = 'open';
    public const REVIEWED = 'reviewed';
    public const RESOLVED = 'resolved';
    public const DISMISSED = 'dismissed';

    public static function all(): array
    {
        return [self::OPEN, self::REVIEWED, self::RESOLVED, self::DISMISSED];
    }

    public static function label(string $status): string
    {
        return ucfirst($status);
    }
}
