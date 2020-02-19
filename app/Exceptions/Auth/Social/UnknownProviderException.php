<?php

declare(strict_types=1);

namespace App\Exceptions\Auth\Social;

use App\Exceptions\BaseException;
use Throwable;

class UnknownProviderException extends BaseException
{
    public function __construct(Throwable $previous = null, array $customData = [])
    {
        parent::__construct($previous, 'Unknown social provider name.', $customData);
    }
}
