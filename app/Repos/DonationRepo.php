<?php

declare(strict_types=1);

namespace App\Repos;

use App\Models\Donation;
use App\Models\Garden;
use App\Models\User;
use App\Support\Money\Money;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class DonationRepo
{
    /**
     * @param int $id
     * @return \App\Models\Donation|null
     */
    public function findById(int $id) : ?Donation
    {
        return Donation::query()
            ->with('garden', 'user')
            ->find($id);
    }

    /**
     * @param string $hashId
     * @return \App\Models\Donation|null
     */
    public function findByHashId(string $hashId) : ?Donation
    {
        return Donation::query()
            ->where('hash_id', $hashId)
            ->with('garden', 'user')
            ->first();
    }

    /**
     * @param \App\Models\Garden $garden
     * @param \App\Support\Money\Money $amount
     * @param int $flowersQuantity
     * @param \App\Models\User|null $user
     * @return \App\Models\Donation
     */
    public function createIncomplete(Garden $garden, Money $amount, int $flowersQuantity, User $user = null) : Donation
    {
        /** @var Donation $donation */
        $donation = $garden->donations()->create([
            'amount'           => $amount->getAmount(),
            'flowers_quantity' => $flowersQuantity,
            'complete'         => false,
            'user_id'          => $user !== null
                ? $user->id
                : null
        ]);

        return $donation;
    }

    /**
     * @param \App\Models\Donation $donation
     * @param string|null $email
     */
    public function setEmail(Donation &$donation, ?string $email) : void
    {
        $donation->update([
            'email' => clear_email($email)
        ]);
    }

    /**
     * @param \App\Models\Donation $donation
     * @param bool $value
     */
    public function setComplete(Donation &$donation, bool $value = true) : void
    {
        $donation->update([
            'complete' => $value
        ]);
    }

    /**
     * @param \App\Models\Donation $donation
     * @param \App\Models\User|null $user
     */
    public function setUser(Donation &$donation, ?User $user) : void
    {
        $donation->user()->associate($user);
        $donation->save();
    }

    /**
     * @param \App\Models\Garden $garden
     * @param bool $completedOnly
     * @return int
     */
    public function sumAmountsByGarden(Garden $garden, bool $completedOnly = true) : int
    {
        $query = $garden->donations();
        if ($completedOnly) {
            $query->where('complete', 1);
        }

        return (int) $query->sum('amount');
    }

    /**
     * @param \Carbon\Carbon|null $olderThan
     * @return \App\Models\Donation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getIncomplete(Carbon $olderThan = null) : Collection
    {
        $query = Donation::query()
            ->where('complete', 0)
            ->orderBy('created_at', 'ASC')
            ->with('flowers');

        if ($olderThan !== null) {
            $query->where('created_at', '<=', $olderThan);
        }

        return $query->get();
    }

    /**
     * @param \App\Models\Donation $donation
     */
    public function delete(Donation $donation) : void
    {
        try {
            $donation->delete();
        } catch (Exception $e) {
        }
    }
}
