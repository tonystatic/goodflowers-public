<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Social;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot() : void
    {
        Route::pattern('id', '^(?!0)[\d]+');
        Route::pattern('slug', '[a-zA-Z0-9\-_]+');
        Route::pattern('hashId', '[a-zA-Z0-9]{6,20}');

        Route::pattern(
            'socialProvider',
            '^(' . \implode('|', Social::getSupportedProviders()) . ')$'
        );

        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map() : void
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(function () : void {
                require base_path('routes/front.php');
                require base_path('routes/service.php');
            });
    }
}
