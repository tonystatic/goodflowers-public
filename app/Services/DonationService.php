<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\DonationCompleted;
use App\Events\DonationCreated;
use App\Models\Donation;
use App\Models\Garden;
use App\Models\User;
use App\Repos\DonationRepo;
use App\Repos\FlowerRepo;
use Illuminate\Http\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Cookie;

class DonationService
{
    /* @var \App\Repos\DonationRepo */
    protected $donationRepo;

    /* @var \App\Services\FlowerService */
    protected $flowerService;

    const POST_DONATION_COOKIE_NAME = 'donation_made';

    const POST_DONATION_COOKIE_MINUTES = 120;

    /**
     * DonationService constructor.
     */
    public function __construct()
    {
        $this->donationRepo = app(DonationRepo::class);
        $this->flowerService = app(FlowerService::class);
    }

    /**
     * @param \App\Models\Garden $garden
     * @param int $flowersQuantity
     * @param \App\Models\User|null $user
     * @return \App\Models\Donation
     */
    public function startDonation(
        Garden $garden,
        int $flowersQuantity,
        User $user = null
    ) : Donation {
        $amount = $this->flowerService->getPriceOfQuantity($flowersQuantity);
        $donation = $this->donationRepo->createIncomplete($garden, $amount, $flowersQuantity, $user);

        event(new DonationCreated($donation));

        $this->flowerService->createFlowers($donation);

        return $donation;
    }

    /**
     * @param \App\Models\Donation $donation
     */
    public function completeDonation(Donation &$donation) : void
    {
        $this->donationRepo->setComplete($donation);

        event(new DonationCompleted($donation));
    }

    /**
     * @param \App\Models\Donation $donation
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function createPostDonationCookie(Donation $donation) : Cookie
    {
        return $cookie = cookie(
            self::POST_DONATION_COOKIE_NAME . '_' . $donation->garden->hash_id,
            $donation->hash_id,
            self::POST_DONATION_COOKIE_MINUTES,
            null,
            null,
            false,
            false
        );
    }

    /**
     * @param \App\Models\Garden $garden
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    public function deletePostDonationCookie(Garden $garden) : Cookie
    {
        return cookie()->forget(
            self::POST_DONATION_COOKIE_NAME . '_' . $garden->hash_id
        );
    }

    /**
     * @param \App\Models\Garden $garden
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\Donation|null
     */
    public function extractDonationFromCookie(Garden $garden, HttpRequest $request) : ?Donation
    {
        $donationHashId = $request->cookie(self::POST_DONATION_COOKIE_NAME . '_' . $garden->hash_id);

        if (! \is_string($donationHashId)) {
            return null;
        }

        /** @var \App\Repos\DonationRepo $donationRepo */
        $donationRepo = app(DonationRepo::class);

        return $donationRepo->findByHashId($donationHashId);
    }

    /**
     * @param \App\Models\Donation $donation
     * @param bool $withFlowers
     */
    public function deleteDonation(Donation $donation, bool $withFlowers = true) : void
    {
        /** @var \App\Repos\DonationRepo $donationRepo */
        $donationRepo = app(DonationRepo::class);
        if ($withFlowers) {
            /** @var \App\Repos\FlowerRepo $flowerRepo */
            $flowerRepo = app(FlowerRepo::class);
            foreach ($donation->flowers as $flower) {
                $flowerRepo->delete($flower);
            }
        }
        $donationRepo->delete($donation);
    }
}
