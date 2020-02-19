<?php

declare(strict_types=1);

namespace App\Http\Transformers\Front;

use App\Models\Flower;
use App\Support\Transformers\BaseTransformer;

class FlowerShortTransformer extends BaseTransformer
{
    /**
     * @param \App\Models\Flower $flower
     * @return array
     */
    public function transform(Flower $flower) : array
    {
        return \array_merge([
            'hash'  => $flower->hash_id,
            'image' => app()->environment() !== 'local'
                ? $flower->file_url
                : "/storage/{$flower->file_path}"
        ]);
    }

    /**
     * @return string
     */
    public function getItemKey() : string
    {
        return 'flower';
    }

    /**
     * @return string
     */
    public function getCollectionKey() : string
    {
        return 'flowers';
    }
}
