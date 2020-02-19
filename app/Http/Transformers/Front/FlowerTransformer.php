<?php

declare(strict_types=1);

namespace App\Http\Transformers\Front;

use App\Models\Flower;
use App\Models\User;
use App\Support\Transformers\BaseTransformer;

class FlowerTransformer extends BaseTransformer
{
    /** @var \App\Models\User|null */
    protected $user;

    /**
     * FlowerTransformer constructor.
     * @param \App\Models\User|null $user
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

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
                : "/storage/{$flower->file_path}",
            'timestamp' => $flower->created_at->timestamp,
            'mine'      => $this->user !== null
            && $flower->donation !== null
            && $this->user->id === $flower->donation->user_id,
            'link' => $flower->donation !== null && $flower->donation->garden !== null
                ? route(
                    'front.garden',
                    [$flower->donation->garden->slug, 'flower' => $flower->hash_id]
                )
                : null,
            'owner' => $flower->donation !== null && $flower->donation->user !== null
                ? [
                    'name' => $flower->donation->user->name,
                    'link' => $flower->donation->user->link
                ]
            : null
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
