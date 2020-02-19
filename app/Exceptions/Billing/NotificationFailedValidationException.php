<?php

declare(strict_types=1);

namespace App\Exceptions\Auth\Billing;

use App\Exceptions\BaseException;
use Throwable;

class NotificationFailedValidationException extends BaseException
{
    public function __construct(Throwable $previous = null, array $customData = [])
    {
        parent::__construct($previous, 'Validation on billing callback failed.', $customData);
    }
}
