<?php

declare(strict_types=1);

namespace App\Support\Seo\Contracts;

use App\Support\Seo\MetaSet;

interface MetaProvider
{
    /**
     * @return MetaSet
     */
    public function getMeta() : MetaSet;
}
