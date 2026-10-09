<?php

use App\Enums\OrderStatus;
use App\Enums\ProductCondition;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Enums\VerificationStatus;

if (! function_exists('bdt')) {
    /**
     * Format an amount as Bangladeshi Taka.
     */
    function bdt(float|int|string|null $amount): string
    {
        return '৳' . number_format((float) $amount, 0);
    }
}

if (! function_exists('order_status_label')) {
    function order_status_label(string $status): string
    {
        return OrderStatus::label($status);
    }
}

if (! function_exists('payment_status_label')) {
    function payment_status_label(string $status): string
    {
        return PaymentStatus::label($status);
    }
}

if (! function_exists('product_status_label')) {
    function product_status_label(string $status): string
    {
        return ProductStatus::label($status);
    }
}

if (! function_exists('condition_label')) {
    function condition_label(string $condition): string
    {
        return ProductCondition::label($condition);
    }
}

if (! function_exists('verification_status_label')) {
    function verification_status_label(string $status): string
    {
        return VerificationStatus::label($status);
    }
}
