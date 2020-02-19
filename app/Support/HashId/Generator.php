<?php

declare(strict_types=1);

namespace App\Support\HashId;

use Hashids\Hashids;

class Generator
{
    /**
     * @param int $id
     * @param int $minLength
     * @return string
     */
    public function generate(int $id, int $minLength = 8) : string
    {
        $hashIds = new Hashids('GoodFlowers', $minLength);

        return $hashIds->encode($id);
    }
}
