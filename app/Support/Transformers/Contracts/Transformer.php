<?php

declare(strict_types=1);

namespace App\Support\Transformers\Contracts;

interface Transformer
{
    /**
     * @return string
     */
    public function getItemKey() : string;

    /**
     * @return string
     */
    public function getCollectionKey() : string;
}
