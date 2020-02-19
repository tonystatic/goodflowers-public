<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Exceptions\Contracts\Exception;
use Exception as CoreException;
use Throwable;

abstract class BaseException extends CoreException implements Exception
{
    protected $customData = [];

    public function __construct(Throwable $previous = null, string $message = '', array $customData = [])
    {
        parent::__construct($message, 0, $previous);
        $this->customData = $customData;
    }

    public function getCustomData() : array
    {
        return $this->customData;
    }
}
