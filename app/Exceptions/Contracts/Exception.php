<?php

declare(strict_types=1);

namespace App\Exceptions\Contracts;

use Throwable;

interface Exception extends Throwable
{
    /**
     * @return array
     */
    public function getCustomData() : array;
}
