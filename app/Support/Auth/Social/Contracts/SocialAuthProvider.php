<?php

declare(strict_types=1);

namespace App\Support\Auth\Social\Contracts;

use App\Support\Auth\Social\User;
use Illuminate\Http\RedirectResponse;

interface SocialAuthProvider
{
    /**
     * @param array $parameters
     * @throws \App\Exceptions\Auth\Social\RedirectObtainmentException
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRedirect(array $parameters = []) : RedirectResponse;

    /**
     * @param bool $stateless
     * @throws \App\Exceptions\Auth\Social\UserObtainmentException
     * @return \App\Support\Auth\Social\User
     */
    public function getUser(bool $stateless = false) : User;

    /**
     * @param array $input
     * @return array
     */
    public function extractParameters(array $input) : array;
}
