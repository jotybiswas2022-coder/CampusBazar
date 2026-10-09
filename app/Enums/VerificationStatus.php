<?php

namespace App\Enums;

class VerificationStatus
{
    public const NOT_SUBMITTED = 'not_submitted';
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const REJECTED = 'rejected';
    public const RESUBMISSION_REQUIRED = 'resubmission_required';

    public static function all(): array
    {
        return [
            self::NOT_SUBMITTED,
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
            self::RESUBMISSION_REQUIRED,
        ];
    }

    public static function label(string $status): string
    {
        return match ($status) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::RESUBMISSION_REQUIRED => 'Resubmission Required',
            default => 'Not Submitted',
        };
    }
}
