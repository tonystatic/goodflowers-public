<?php

declare(strict_types=1);

namespace App\Support\Transformers;

use League\Fractal\Serializer\ArraySerializer as DefaultArraySerializer;

class ArraySerializer extends DefaultArraySerializer
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data) : array
    {
        return $resourceKey ? [$resourceKey => $data] : $data;
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function item($resourceKey, array $data) : array
    {
        return $resourceKey ? [$resourceKey => $data] : $data;
    }
}
