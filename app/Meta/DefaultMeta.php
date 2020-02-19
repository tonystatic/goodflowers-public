<?php

declare(strict_types=1);

namespace App\Meta;

use App\Support\Seo\MetaSet;

class DefaultMeta extends BaseMeta
{
    /**
     * @return MetaSet
     */
    public function getMeta() : MetaSet
    {
        return $this->getDefaultMeta();
    }
}
