<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'cloudpayments' => [
        'public'  => env('CLOUDPAYMENTS_PUBLIC'),
        'private' => env('CLOUDPAYMENTS_PRIVATE')
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_KEY'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect'      => env('FACEBOOK_REDIRECT_URI')
    ],

    'instagram' => [
        'client_id'     => env('INSTAGRAM_KEY'),
        'client_secret' => env('INSTAGRAM_SECRET'),
        'redirect'      => env('INSTAGRAM_REDIRECT_URI')
    ],

    'twitter' => [
        'client_id'     => env('TWITTER_KEY'),
        'client_secret' => env('TWITTER_SECRET'),
        'redirect'      => env('TWITTER_REDIRECT_URI'),
        'site_account'  => null_if_empty_string(env('TWITTER_SITE_ACCOUNT'))
    ],

    'vkontakte' => [
        'client_id'     => env('VKONTAKTE_KEY'),
        'client_secret' => env('VKONTAKTE_SECRET'),
        'redirect'      => env('VKONTAKTE_REDIRECT_URI')
    ],

    'jivosite' => [
        'id' => env('JIVOSITE_ID')
    ],

    'yandex_metrika' => [
        'id' => env('YANDEX_METRIKA_ID')
    ],

    'google_tag_manager' => [
        'id' => env('GOOGLE_TAG_MANAGER_ID')
    ],

    'facebook_pixel' => [
        'id' => env('FACEBOOK_PIXEL_ID')
    ],

];
