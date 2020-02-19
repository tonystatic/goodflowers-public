<?php

declare(strict_types=1);

namespace App\Exceptions\Auth\Social;

use App\Exceptions\BaseException;
use Throwable;

class RedirectObtainmentException extends BaseException
{
    public function __construct(Throwable $previous = null, array $customData = [])
    {
        parent::__construct($previous, 'Social auth redirect cannot be obtained.', $customData);
    }
}
