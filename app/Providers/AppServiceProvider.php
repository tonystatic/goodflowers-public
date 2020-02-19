<?php

declare(strict_types=1);

namespace App\Providers;

use App\Meta\DefaultMeta;
use App\Models;
use App\Models\Observers;
use App\Support\Billing\Contracts\BillingProvider;
use App\Support\Billing\Providers\CloudPayments;
use App\Support\ErrorNotification\Contracts\ErrorNotificationProvider;
use App\Support\ErrorNotification\Providers\Bugsnug;
use App\Support\Seo\MetaContainer;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register() : void
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->bind(BillingProvider::class, function () {
            return new CloudPayments();
        });

        $this->app->bind(ErrorNotificationProvider::class, function () {
            return new Bugsnug();
        });

        $this->app->singleton(MetaContainer::class, function () {
            return new MetaContainer((new DefaultMeta())->getMeta());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        if (request()->server->has('HTTP_X_ORIGINAL_HOST')) {
            request()->server->set('HTTP_HOST', request()->server->get('HTTP_X_ORIGINAL_HOST'));
            request()->headers->set('HOST', request()->server->get('HTTP_X_ORIGINAL_HOST'));

            if (request()->server->get('HTTP_X_FORWARDED_PROTO') === 'https') {
                URL::forceScheme('https');
            }
        }

        Relation::morphMap([
            'user'     => Models\User::class,
            'donation' => Models\Donation::class
        ]);

        // Model Observers
        Models\Flower::observe(Observers\FlowerObserver::class);
    }
}
