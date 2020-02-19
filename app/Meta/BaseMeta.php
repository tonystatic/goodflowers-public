<?php

declare(strict_types=1);

namespace App\Meta;

use App\Support\Seo\Contracts\MetaProvider;
use App\Support\Seo\MetaSet;

abstract class BaseMeta implements MetaProvider
{
    /**
     * @return MetaSet
     */
    protected function getDefaultMeta() : MetaSet
    {
        return new MetaSet(
            trans('seo.default.title'),
            trans('seo.default.description'),
            (array) trans('seo.default.keywords'),
            trans('seo.default.imageUrl')
        );
    }
}
