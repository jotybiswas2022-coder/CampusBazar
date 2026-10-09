<?php

return [
    /*
    |--------------------------------------------------------------------------
    | CampusBazar configuration
    |--------------------------------------------------------------------------
    */

    // Flat cash-on-delivery charge in BDT. Always calculated on the server.
    'delivery_charge' => (int) env('CAMPUSBAZAR_DELIVERY_CHARGE', 60),

    // Maximum number of products a seller may keep active at once.
    'max_active_listings' => (int) env('CAMPUSBAZAR_MAX_LISTINGS', 50),

    // Number of products shown per page.
    'per_page' => 12,

    // Retention period (in days) for approved verification documents before
    // they are eligible for the scheduled cleanup policy.
    'verification_retention_days' => (int) env('CAMPUSBAZAR_VERIFICATION_RETENTION', 365),
];
