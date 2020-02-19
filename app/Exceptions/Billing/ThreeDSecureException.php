<?php

declare(strict_types=1);

namespace App\Exceptions\Billing;

use App\Exceptions\BaseException;
use Throwable;

class ThreeDSecureException extends BaseException
{
    public function __construct(Throwable $previous = null, array $customData = [])
    {
        parent::__construct($previous, '3DSecure failed.', $customData);
    }
}
