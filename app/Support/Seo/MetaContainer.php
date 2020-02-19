<?php

declare(strict_types=1);

namespace App\Support\Seo;

class MetaContainer
{
    /** @var \App\Support\Seo\MetaSet */
    protected $meta;

    public function __construct(MetaSet $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return \App\Support\Seo\MetaSet
     */
    public function getMeta() : MetaSet
    {
        return $this->meta;
    }

    /**
     * @param \App\Support\Seo\MetaSet $meta
     * @return \App\Support\Seo\MetaContainer
     */
    public function setMeta(MetaSet $meta) : MetaContainer
    {
        $this->meta = $meta;

        return $this;
    }
}
