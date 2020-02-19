<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DonationSigned;
use App\Repos\TransactionRepo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SignConnectedTransaction implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param \App\Events\DonationSigned $event
     */
    public function handle(DonationSigned $event) : void
    {
        $donation = $event->getDonation();
        $user = $donation->user;

        if ($user !== null) {
            /* @var \App\Repos\TransactionRepo $transactionRepo */
            $transactionRepo = app(TransactionRepo::class);
            $transactionRepo->setUserToAllByDonation($donation, $user);
        }
    }
}
