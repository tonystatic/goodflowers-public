<?php

declare(strict_types=1);

namespace App\Support\Auth\Social\Providers;

use App\Support\Auth\Social\Contracts\SocialAuthProvider;
use Exception;

abstract class BaseProvider implements SocialAuthProvider
{
    /**
     * @param array $parameters
     * @return array
     */
    protected function wrapParameters(array $parameters = []) : array
    {
        return ['state' => \urlencode(\http_build_query($parameters))];
    }

    /**
     * {@inheritdoc}
     */
    public function extractParameters(array $input) : array
    {
        if (isset($input['state'])) {
            $stateArray = [];

            try {
                $stateString = \urldecode($input['state']);
                \parse_str($stateString, $stateArray);
                $input = \array_merge($input, $stateArray);
            } catch (Exception $e) {
            }
            unset($input['state']);
        }

        return $input;
    }
}
