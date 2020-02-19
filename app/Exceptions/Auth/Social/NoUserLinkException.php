<?php

declare(strict_types=1);

namespace App\Exceptions\Auth\Social;

use App\Exceptions\BaseException;
use Throwable;

class NoUserLinkException extends BaseException
{
    public function __construct(Throwable $previous = null, array $customData = [])
    {
        parent::__construct($previous, 'No user link extracted.', $customData);
    }
}
