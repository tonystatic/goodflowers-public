<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events;
use App\Listeners;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Facebook\FacebookExtendSocialite;
use SocialiteProviders\Instagram\InstagramExtendSocialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Twitter\TwitterExtendSocialite;
use SocialiteProviders\VKontakte\VKontakteExtendSocialite;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SocialiteWasCalled::class => [
            FacebookExtendSocialite::class,
            InstagramExtendSocialite::class,
            TwitterExtendSocialite::class,
            VKontakteExtendSocialite::class
        ],
        Events\DonationCompleted::class => [
            Listeners\RefreshGardenValue::class
        ],
        Events\DonationSigned::class => [
            Listeners\SignConnectedTransaction::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot() : void
    {
        parent::boot();

        //
    }
}
