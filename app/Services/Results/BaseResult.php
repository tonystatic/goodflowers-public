<?php

declare(strict_types=1);

namespace App\Services\Results;

abstract class BaseResult
{
    /** @var int|null */
    protected $error = null;

    /**
     * BaseResult constructor.
     * @param int|null $error
     */
    public function __construct(int $error = null)
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function successful() : bool
    {
        return $this->error === null;
    }

    /**
     * @return bool
     */
    public function failed() : bool
    {
        return $this->error !== null;
    }

    /**
     * @return int|null
     */
    public function error() : ?int
    {
        return $this->error;
    }
}
