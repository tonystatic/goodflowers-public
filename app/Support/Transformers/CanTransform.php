<?php

declare(strict_types=1);

namespace App\Support\Transformers;

use App\Support\Transformers\Contracts\Transformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\ResourceAbstract;

trait CanTransform
{
    /**
     * @return \League\Fractal\Manager
     */
    protected function getTransformManager() : Manager
    {
        return (new Manager())->setSerializer(new ArraySerializer());
    }

    /**
     * @param $item
     * @param \App\Support\Transformers\Contracts\Transformer $transformer
     * @param string|null $resourceKey
     * @return array|null
     */
    protected function transformItem($item, Transformer $transformer, string $resourceKey = null) : ?array
    {
        $resourceKey = ($resourceKey === null ? $transformer->getItemKey() : $resourceKey);

        if ($item === null) {
            return ($resourceKey === null) ? null : [$resourceKey => null];
        }

        return $this->transformResource(new Item($item, $transformer, $resourceKey));
    }

    /**
     * @param $items
     * @param \App\Support\Transformers\Contracts\Transformer $transformer
     * @param string|null $resourceKey
     * @return array
     */
    protected function transformCollection($items, Transformer $transformer, string $resourceKey = null) : array
    {
        $resourceKey = ($resourceKey === null ? $transformer->getCollectionKey() : $resourceKey);

        return $this->transformResource(new Collection($items, $transformer, $resourceKey));
    }

    /**
     * @param \League\Fractal\Resource\ResourceAbstract $resource
     * @return array
     */
    private function transformResource(ResourceAbstract $resource) : array
    {
        $transformManager = $this->getTransformManager();

        return $transformManager->createData($resource)->toArray();
    }
}
