<?php

declare(strict_types=1);

namespace App\Support\Response;

use Arr;
use Request;
use Symfony\Component\HttpFoundation\Response;

class StandardResponse
{
    const TYPE_NORMAL = 'normal';

    const TYPE_JSON = 'json';

    /** @var string|null */
    protected $forcedType;

    /**
     * StandardResponse constructor.
     *
     * @param string|null $forcedType
     */
    public function __construct(string $forcedType = null)
    {
        $this->forcedType = $forcedType;
    }

    /**
     * @param bool $success
     * @param string|null $destination
     * @param array $data
     * @param bool $guestRedirect
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function response(
        bool $success = true,
        string $destination = null,
        array $data = [],
        bool $guestRedirect = false
    ) : Response {
        if (
            $this->forcedType !== self::TYPE_NORMAL
            && (
                $this->forcedType === self::TYPE_JSON
                || Request::ajax()
                || Request::wantsJson()
            )
        ) {
            $jsonData = [
                'success' => $success
            ];
            if ($success) {
                $jsonData['message'] = $this->extractMessageForJson($data);
            } else {
                $jsonData['messages'] = $this->extractMessagesForJson($data);
            }
            $jsonData['data'] = $data;

            return response()->json($jsonData);
        }

        if ($guestRedirect === true && $destination !== null) {
            $redirect = redirect()->guest($destination);
        } elseif ($destination !== null) {
            $redirect = redirect()->to($destination);
        } elseif ($success === true) {
            $redirect = redirect()->intended();
        } else {
            $redirect = redirect()->back();
        }

        $redirect = $redirect->with($data);

        if (! $success) {
            $redirect = $redirect->withInput();
        }

        return $redirect;
    }

    /**
     * @param array $data
     * @return string|null
     */
    protected function extractMessageForJson(array &$data) : ?string
    {
        $message = null;
        $message = $this->extractParam($data, 'success');
        if ($message === null) {
            $message = $this->extractParam($data, 'warning');
        }
        if ($message === null) {
            $message = $this->extractParam($data, 'error');
        }
        if ($message === null) {
            $message = $this->extractParam($data, 'errors');
        }

        return $message;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function extractMessagesForJson(array &$data) : array
    {
        return \array_merge(
            $this->extractArrayParam($data, 'success'),
            $this->extractArrayParam($data, 'warning'),
            $this->extractArrayParam($data, 'error'),
            $this->extractArrayParam($data, 'errors')
        );
    }

    /**
     * @param array $array
     * @param string $key
     * @return string|null
     */
    protected function extractParam(array &$array, string $key) : ?string
    {
        $value = null;
        if (isset($array[$key])) {
            $value = \is_array($array[$key])
                ? Arr::first($array[$key])
                : (
                    \is_string($array[$key])
                    ? $array[$key]
                    : null
                );
            unset($array[$key]);
        }

        return $value;
    }

    /**
     * @param array $array
     * @param string $key
     * @return array
     */
    protected function extractArrayParam(array &$array, string $key) : array
    {
        $value = [];
        if (isset($array[$key])) {
            $value = \is_array($array[$key])
                ? $array[$key]
                : (
                    \is_string($array[$key])
                        ? [$array[$key]]
                        : []
                );
            unset($array[$key]);
        }

        return $value;
    }

    /**
     * @param string|null $message
     * @param string|null $destination
     * @param array $data
     * @param bool $guestRedirect
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success(
        string $message = null,
        string $destination = null,
        array $data = [],
        bool $guestRedirect = false
    ) : Response {
        if ($message !== null) {
            $data = \array_merge($data, ['success' => $message]);
        }

        return $this->response(true, $destination, $data, $guestRedirect);
    }

    /**
     * @param bool $success
     * @param string|null $message
     * @param string|null $destination
     * @param array $data
     * @param bool $guestRedirect
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function warning(
        bool $success = true,
        string $message = null,
        string $destination = null,
        array $data = [],
        bool $guestRedirect = false
    ) : Response {
        if ($message !== null) {
            $data = \array_merge($data, ['warning' => $message]);
        }

        return $this->response($success, $destination, $data, $guestRedirect);
    }

    /**
     * @param string|null $message
     * @param string|null $destination
     * @param array $data
     * @param bool $guestRedirect
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function error(
        string $message = null,
        string $destination = null,
        array $data = [],
        bool $guestRedirect = false
    ) : Response {
        if ($message !== null) {
            $data = \array_merge($data, ['error' => $message]);
        }

        return $this->response(false, $destination, $data, $guestRedirect);
    }

    /**
     * @param array|null $messages
     * @param string|null $destination
     * @param array $data
     * @param bool $guestRedirect
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function errors(
        array $messages = null,
        string $destination = null,
        array $data = [],
        bool $guestRedirect = false
    ) : Response {
        if ($messages !== null) {
            $data = \array_merge($data, ['errors' => $messages]);
        }

        return $this->response(false, $destination, $data, $guestRedirect);
    }
}
