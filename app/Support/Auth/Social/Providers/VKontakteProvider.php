<?php

declare(strict_types=1);

namespace App\Support\Auth\Social\Providers;

use App\Exceptions\Auth\Social\RedirectObtainmentException;
use App\Exceptions\Auth\Social\UserObtainmentException;
use App\Support\Auth\Social\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Socialite;

class VKontakteProvider extends BaseProvider
{
    /**
     * {@inheritdoc}
     */
    public function getRedirect(array $parameters = []) : RedirectResponse
    {
        try {
            return Socialite::driver('vkontakte')
                ->scopes(['email'])
                ->with($this->wrapParameters($parameters))
                ->redirect();
        } catch (Exception $e) {
            throw new RedirectObtainmentException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(bool $stateless = false) : User
    {
        try {
            /** @var \Laravel\Socialite\Two\User|\SocialiteProviders\Manager\OAuth2\User $user */
            $user = Socialite::driver('vkontakte')
                ->stateless($stateless)
                ->user();
        } catch (Exception $e) {
            throw new UserObtainmentException();
        }

        $email = $user->getEmail();
        if ($email === null && isset($user->accessTokenResponseBody['email'])) {
            $email = $user->accessTokenResponseBody['email'];
        }

        $link = 'https://vk.com/' . $user->getNickname();

        return new User($email, (string) $user->getId(), (string) $user->getName(), $link);
    }
}
