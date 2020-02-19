<?php

declare(strict_types=1);

namespace App\Support\ErrorNotification\Contracts;

use Exception;

interface ErrorNotificationProvider
{
    const SEVERITY_ERROR = 'error';

    const SEVERITY_WARNING = 'warning';

    const SEVERITY_INFO = 'info';

    /**
     * @param \Exception $exception
     * @param array $data
     * @param string $severity
     */
    public function notifyException(
        Exception $exception,
        array $data = [],
        string $severity = self::SEVERITY_ERROR
    ) : void;

    /**
     * @param string $name
     * @param string|null $description
     * @param array $data
     * @param string $severity
     */
    public function notifyError(
        string $name,
        string $description = null,
        array $data = [],
        string $severity = self::SEVERITY_ERROR
    ) : void;
}
