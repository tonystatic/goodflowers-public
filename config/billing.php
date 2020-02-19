<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Billing-related settings
    |--------------------------------------------------------------------------
    */

    'enabled' => env('BILLING_ENABLED', true),

    '3ds_callback_proxy' => null_if_empty_string(env('BILLING_3DS_CALLBACK_PROXY')),

];
