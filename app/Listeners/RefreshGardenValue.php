<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DonationCompleted;
use App\Repos\DonationRepo;
use App\Repos\GardenRepo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RefreshGardenValue implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param \App\Events\DonationCompleted $event
     */
    public function handle(DonationCompleted $event) : void
    {
        $donation = $event->getDonation();
        $garden = $donation->garden;

        if ($garden !== null) {
            /* @var \App\Repos\GardenRepo $gardenRepo */
            $gardenRepo = app(GardenRepo::class);
            /* @var \App\Repos\DonationRepo $donationRepo */
            $donationRepo = app(DonationRepo::class);

            $gardenRepo->updateTotalValue(
                $garden,
                $donationRepo->sumAmountsByGarden($garden)
            );
        }
    }
}
