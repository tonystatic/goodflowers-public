<?php

declare(strict_types=1);

namespace App\Repos;

use App\Models\Social;
use App\Models\User;
use Carbon\Carbon;

class UserRepo
{
    /**
     * @param int $id
     * @return \App\Models\User|null
     */
    public function findById(int $id) : ?User
    {
        /* @var User|null $user */
        $user = User::query()
            ->with('socials')
            ->find($id);

        return $user;
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Social $social
     * @param bool $forceUpdate
     */
    public function updateFromSocial(User &$user, Social $social, bool $forceUpdate = false) : void
    {
        $data = [];
        if ($social->name !== null && ($forceUpdate || $user->name === null)) {
            $data['name'] = $social->name;
        }
        if ($social->link !== null && ($forceUpdate || $user->link === null)) {
            $data['link'] = $social->link;
        }
        if (\count($data) > 0) {
            $user->update($data);
        }
    }

    /**
     * @param string $email
     * @return \App\Models\User|null
     */
    public function findByEmail(string $email) : ?User
    {
        /* @var User|null $user */
        $user = User::query()
            ->where('email', clear_email($email))
            ->first();

        return $user;
    }

    /**
     * @param string $email
     * @param bool $emailConfirmed
     * @param bool $setLastActive
     * @return \App\Models\User
     */
    public function findOrCreateByEmail(
        string $email,
        bool $emailConfirmed = false,
        bool $setLastActive = true
    ) : User {
        /* @var User $user */
        $user = User::query()
            ->firstOrCreate(
                [
                    'email' => clear_email($email)
                ],
                [
                    'email_confirmed' => $emailConfirmed,
                    'last_active_at'  => $setLastActive === true
                        ? Carbon::now()->toDateTimeString()
                        : null
                ]
            );

        return $user;
    }

    /**
     * @param bool $setLastActive
     * @return \App\Models\User|null
     */
    public function createEmpty(
        bool $setLastActive = true
    ) : ?User {
        /* @var User $user */
        $user = User::query()
            ->create([
                'last_active_at' => $setLastActive === true
                    ? Carbon::now()->toDateTimeString()
                    : null
            ]);

        return $user;
    }

    /**
     * @param \App\Models\User $user
     */
    public function updateLastActive(User &$user) : void
    {
        $user->update([
            'last_active_at' => Carbon::now()->toDateTimeString()
        ]);
    }

    /**
     * @param \App\Models\User $user
     * @param array $attributes
     */
    public function patch(User &$user, array $attributes) : void
    {
        $user->update($attributes);
    }

    /**
     * @param \App\Models\User $user
     * @param string $provider
     * @return bool
     */
    public function hasSocialsOfProvider(User $user, string $provider) : bool
    {
        return $user->socials()
            ->where('provider', $provider)
            ->exists();
    }
}
