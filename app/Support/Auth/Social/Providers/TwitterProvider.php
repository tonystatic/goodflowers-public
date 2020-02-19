<?php

declare(strict_types=1);

namespace App\Support\Auth\Social\Providers;

use App\Exceptions\Auth\Social\RedirectObtainmentException;
use App\Exceptions\Auth\Social\UserObtainmentException;
use App\Support\Auth\Social\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Socialite;

class TwitterProvider extends BaseProvider
{
    /**
     * {@inheritdoc}
     */
    public function getRedirect(array $parameters = []) : RedirectResponse
    {
        try {
            return Socialite::driver('Twitter')
                ->with($this->wrapParameters($parameters))
                ->redirect();
        } catch (Exception $e) {
            throw new RedirectObtainmentException($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(bool $stateless = false) : User
    {
        try {
            /** @var \Laravel\Socialite\One\User $user */
            $user = Socialite::driver('Twitter')
                ->stateless($stateless)
                ->user();
        } catch (Exception $e) {
            throw new UserObtainmentException($e);
        }

        $link = 'https://twitter.com/' . $user->getNickname();

        return new User($user->getEmail(), (string) $user->getId(), (string) $user->getName(), $link);
    }
}
