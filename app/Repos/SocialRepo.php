<?php

declare(strict_types=1);

namespace App\Repos;

use App\Models\Social;
use App\Models\User;
use App\Support\Auth\Social\User as SocialUser;
use Carbon\Carbon;

class SocialRepo
{
    /**
     * @param string $provider
     * @param string $extId
     * @return \App\Models\Social|null
     */
    public function findByExtId(string $provider, string $extId) : ?Social
    {
        return Social::query()
            ->where('provider', $provider)
            ->where('ext_id', $extId)
            ->first();
    }

    /**
     * @param string $provider
     * @param \App\Support\Auth\Social\User $socialUser
     * @param bool $setLastLogin
     * @return \App\Models\Social
     */
    public function createFromSocialUser(string $provider, SocialUser $socialUser, bool $setLastLogin = true) : Social
    {
        /* @var Social $social */
        $social = Social::query()
            ->create([
                'provider'      => $provider,
                'ext_id'        => $socialUser->getId(),
                'email'         => $socialUser->getEmail(),
                'name'          => $socialUser->getName(),
                'link'          => $socialUser->getLink(),
                'last_login_at' => $setLastLogin === true
                    ? Carbon::now()->toDateTimeString()
                    : null
            ]);

        return $social;
    }

    /**
     * @param \App\Models\Social $social
     * @param \App\Support\Auth\Social\User $socialUser
     * @param bool $updateLastLogin
     */
    public function updateFromSocialUser(Social &$social, SocialUser $socialUser, bool $updateLastLogin = true) : void
    {
        $social->update(\array_merge(
            [
                'email' => $socialUser->getEmail() !== null ? $socialUser->getEmail() : $social->email,
                'name'  => $socialUser->getName(),
                'link'  => $socialUser->getLink()
            ],
            $updateLastLogin === true
                ? [
                    'last_login_at' => Carbon::now()->toDateTimeString()
                ] : []
        ));
    }

    /**
     * @param \App\Models\Social $social
     */
    public function updateLastLogin(Social &$social) : void
    {
        $social->update([
            'last_login_at' => Carbon::now()->toDateTimeString()
        ]);
    }

    /**
     * @param \App\Models\Social $social
     * @param \App\Models\User $user
     */
    public function setUser(Social &$social, User $user) : void
    {
        $social->user()->associate($user);
        $social->save();
    }
}
