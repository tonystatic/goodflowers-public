<?php

declare(strict_types=1);

namespace App\Meta;

use App\Models\Flower;
use App\Support\Seo\MetaSet;

class FlowerMeta extends BaseMeta
{
    /** @var \App\Models\Flower */
    protected $flower;

    /**
     * GardenMeta constructor.
     * @param \App\Models\Flower $flower
     */
    public function __construct(Flower $flower)
    {
        $this->flower = $flower;
    }

    /**
     * @return MetaSet
     */
    public function getMeta() : MetaSet
    {
        $meta = $this->getDefaultMeta();

        if (($garden = $this->flower->donation->garden) !== null) {
            $meta->setDescription("Цветок в благотворительном саду {$garden->owner_name}.");
            $meta->setImageUrl(asset('assets/front/img/seo/flower.jpg?v=1'))
                ->addKeywords(['сад', 'благотоворительный сад', $garden->owner_name]);
        }

        return $meta;
    }
}
