<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\Auth\Social\UnknownProviderException;
use App\Models\Social;
use App\Models\User;
use App\Repos\SocialRepo;
use App\Repos\UserRepo;
use App\Support\Auth\Social\Contracts\SocialAuthProvider;
use App\Support\Auth\Social\Providers\FacebookProvider;
use App\Support\Auth\Social\Providers\InstagramProvider;
use App\Support\Auth\Social\Providers\TwitterProvider;
use App\Support\Auth\Social\Providers\VKontakteProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SocialAuthService
{
    /**
     * @param string $providerKey
     * @throws \App\Exceptions\Auth\Social\UnknownProviderException
     * @return \App\Support\Auth\Social\Contracts\SocialAuthProvider
     */
    protected function provider(string $providerKey) : SocialAuthProvider
    {
        $providerKey = \mb_strtolower(\trim($providerKey));

        switch ($providerKey) {
            case Social::PROVIDER_FACEBOOK:
                return new FacebookProvider();
            case Social::PROVIDER_INSTAGRAM:
                return new InstagramProvider();
            case Social::PROVIDER_VK:
                return new VKontakteProvider();
            case Social::PROVIDER_TWITTER:
                return new TwitterProvider();
        }

        throw new UnknownProviderException();
    }

    /**
     * @param string $provider
     * @param array $parameters
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function getRedirect(string $provider, array $parameters = []) : ?RedirectResponse
    {
        try {
            return $this->provider($provider)->getRedirect($parameters);
        } catch (Exception $e) {
            error_notification()->notifyException($e);

            return null;
        }
    }

    /**
     * @param string $provider
     * @param \Illuminate\Http\Request $request
     */
    public function extractParameters(string $provider, Request &$request) : void
    {
        try {
            $request->replace($this->provider($provider)->extractParameters($request->all()));
        } catch (Exception $e) {
            error_notification()->notifyException($e);
        }
    }

    /**
     * @param string $provider
     * @param bool $stateless
     * @return \App\Models\Social|null
     */
    public function getSocialOnCallback(string $provider, bool $stateless = false) : ?Social
    {
        try {
            $socialUser = $this->provider($provider)->getUser($stateless);
        } catch (Exception $e) {
            error_notification()->notifyException($e);

            return null;
        }
        /* @var \App\Repos\SocialRepo $socialRepo */
        $socialRepo = app(SocialRepo::class);

        $social = $socialRepo->findByExtId($provider, $socialUser->getId());

        if ($social === null) {
            $social = $socialRepo->createFromSocialUser($provider, $socialUser);
        } else {
            $socialRepo->updateFromSocialUser($social, $socialUser);
        }

        return $social;
    }

    /**
     * @param \App\Models\Social $social
     * @return \App\Models\User
     */
    public function findOrRegisterUserBySocial(Social &$social) : User
    {
        /* @var \App\Repos\UserRepo $userRepo */
        $userRepo = app(UserRepo::class);

        $user = $social->user;
        if ($user === null) {
            /** @var \App\Repos\SocialRepo $socialRepo */
            $socialRepo = app(SocialRepo::class);

            if ($social->email !== null) {
                $user = $userRepo->findOrCreateByEmail($social->email, true);
            } else {
                $user = $userRepo->createEmpty(true);
            }
            $socialRepo->setUser($social, $user);
        }
        $userRepo->updateFromSocial($user, $social);

        return $user;
    }
}
