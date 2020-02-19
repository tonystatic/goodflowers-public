<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\Seo\MetaContainer;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot() : void
    {
        ViewFacade::composer('front.partials.meta-set', function (View $view) : void {
            /* @var MetaContainer $metaContainer */
            $metaContainer = app(MetaContainer::class);
            $meta = $metaContainer->getMeta();

            $view->with('title', $meta->getTitle())
                ->with('description', $meta->getDescription())
                ->with('keywords', $meta->getKeywords())
                ->with('imageUrl', $meta->getImageUrl());
        });
    }
}
