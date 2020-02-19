<?php

declare(strict_types=1);

namespace App\Support\Transformers;

use App\Support\Transformers\Contracts\Transformer;
use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract implements Transformer
{
    use CanTransform;

    /**
     * @return string
     */
    abstract public function getItemKey() : string;

    /**
     * @return string
     */
    abstract public function getCollectionKey() : string;
}
