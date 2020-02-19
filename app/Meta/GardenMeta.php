<?php

declare(strict_types=1);

namespace App\Meta;

use App\Models\Garden;
use App\Support\Seo\MetaSet;

class GardenMeta extends BaseMeta
{
    /** @var \App\Models\Garden */
    protected $garden;

    /**
     * GardenMeta constructor.
     * @param \App\Models\Garden $garden
     */
    public function __construct(Garden $garden)
    {
        $this->garden = $garden;
    }

    /**
     * @return MetaSet
     */
    public function getMeta() : MetaSet
    {
        $meta = $this->getDefaultMeta();

        if ($this->garden->total_value->getValue() !== (float) 0) {
            $meta->setDescription(
                "Сад {$this->garden->owner_name} уже собрал {$this->garden->total_value->getFormattedValue(true)}."
            );
        } else {
            $meta->setDescription(
                "Благотворительный сад {$this->garden->owner_name}."
            );
        }
        $meta->setImageUrl(asset('assets/front/img/seo/garden.jpg?v=1'))
            ->addKeywords(['сад', 'благотоворительный сад', $this->garden->owner_name]);

        return $meta;
    }
}
