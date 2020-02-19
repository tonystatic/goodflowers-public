<?php

declare(strict_types=1);

use App\Support\ErrorNotification\Contracts\ErrorNotificationProvider;
use App\Support\Response\StandardResponse;

// Guards
const GUARD_FRONT = 'front';

// Disks
const DISK_LOCAL = 'local';
const DISK_PUBLIC = 'public';

if (! function_exists('standard_response')) {
    /**
     * Standard response service.
     *
     * @param string|null $forcedType
     * @return \App\Support\Response\StandardResponse
     */
    function standard_response(string $forcedType = null) : StandardResponse
    {
        return app(StandardResponse::class, ['forcedType' => $forcedType]);
    }
}

if (! function_exists('error_notification')) {
    /**
     * Error logging service.
     *
     * @return \App\Support\ErrorNotification\Contracts\ErrorNotificationProvider
     */
    function error_notification() : ErrorNotificationProvider
    {
        return app(ErrorNotificationProvider::class);
    }
}

if (! function_exists('clear_email')) {
    /**
     * @param string $email
     * @return string
     */
    function clear_email(string $email) : string
    {
        return mb_strtolower(trim($email));
    }
}

if (! function_exists('null_if_empty_string')) {
    /**
     * @param string|null $string
     * @return string|null
     */
    function null_if_empty_string(?string $string) : ?string
    {
        return $string === '' ? null : $string;
    }
}

if (! function_exists('mb_split_string')) {
    /**
     * @param string $string
     * @return array
     */
    function mb_split_string(string $string) : array
    {
        $result = [];
        for ($i = 0; $i <= mb_strlen($string) - 1; ++$i) {
            $result[] = mb_substr($string, $i, 1);
        }

        return $result;
    }
}

if (! function_exists('str_limit_careful')) {
    /**
     * Limits string preserving words.
     *
     * @param string $value
     * @param int $limit
     * @param string $end
     * @return string
     */
    function str_limit_careful(string $value, int $limit = 100, string $end = '…') : string
    {
        $value = trim($value);
        $limit = abs($limit);
        $length = mb_strlen($value);

        if ($length > $limit) {
            $firstWordEnd = mb_strpos($value, ' ');
            if ($firstWordEnd === false || (int) $firstWordEnd + 1 >= $limit) {
                $value = mb_substr($value, 0, $limit) . $end;
            } else {
                $value = preg_replace("/^(.{1,$limit})(\\s.*|$)/s", '\\1' . $end, $value);
            }
        }

        return $value;
    }
}
