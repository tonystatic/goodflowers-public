<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\DonateRequest;
use App\Http\Requests\Front\PayRequest;
use App\Http\Transformers\Front\FlowerShortTransformer;
use App\Http\Transformers\Front\PaymentDataTransformer;
use App\Repos\DonationRepo;
use App\Repos\FlowerRepo;
use App\Repos\GardenRepo;
use App\Services\BillingService;
use App\Services\DonationService;
use Cookie;

class DonationController extends BaseController
{
    /**
     * @param string $gardenSlug
     * @param \App\Http\Requests\Front\DonateRequest $request
     * @param \App\Services\DonationService $donationService
     * @param \App\Repos\FlowerRepo $flowerRepo
     * @param \App\Repos\GardenRepo $gardenRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function donate(
        string $gardenSlug,
        DonateRequest $request,
        DonationService $donationService,
        FlowerRepo $flowerRepo,
        GardenRepo $gardenRepo
    ) {
        $garden = $gardenRepo->findBySlug($gardenSlug);
        if ($garden === null) {
            return standard_response()
                ->warning(false, 'Сад не найден.', route('front.index'));
        }

        $donation = $donationService->startDonation(
            $garden,
            (int) $request->input('quantity')
        );

        $flowers = $flowerRepo->getFromDonation($donation, 3);

        return standard_response()->success(
            'Цветы созданы! Пожалуйста, оплатите их, чтобы посадить.',
            null,
            \array_merge([
                'pay_url'          => route('front.donation.pay', $donation->hash_id),
                'paid_url'         => route('front.donation.paid', $donation->hash_id),
                'flowers_quantity' => $donation->flowers_quantity
            ], $this->transformCollection(
                $flowers,
                new FlowerShortTransformer()
            ))
        );
    }

    /**
     * @param string $donationHashId
     * @param \App\Http\Requests\Front\PayRequest $request
     * @param \App\Repos\DonationRepo $donationRepo
     * @param \App\Services\BillingService $billingService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pay(
        string $donationHashId,
        PayRequest $request,
        DonationRepo $donationRepo,
        BillingService $billingService
    ) {
        $donation = $donationRepo->findByHashId($donationHashId);

        if ($donation === null || $donation->garden === null) {
            return standard_response()->error('Покупка не найдена.');
        }

        if ($donation->complete) {
            return standard_response()->success(
                'Эта покупка уже оплачена.',
                route('front.garden', $donation->garden->slug)
            );
        }

        $donationRepo->setEmail($donation, $request->input('email'));

        $paymentData = $billingService->getPaymentDataForDonation(
            $donation
        );

        return standard_response()->success(
            'Произведите оплату.',
            null,
            $this->transformItem($paymentData, new PaymentDataTransformer())
        );
    }

    /**
     * @param string $donationHashId
     * @param \App\Repos\DonationRepo $donationRepo
     * @param \App\Services\BillingService $billingService
     * @param \App\Services\DonationService $donationService
     * @param \App\Repos\FlowerRepo $flowerRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function paid(
        string $donationHashId,
        DonationRepo $donationRepo,
        BillingService $billingService,
        DonationService $donationService,
        FlowerRepo $flowerRepo
    ) {
        $donation = $donationRepo->findByHashId($donationHashId);

        if ($donation === null || $donation->garden === null) {
            return standard_response()->error('Покупка не найдена.');
        }

        if (! $donation->complete) {
            $result = $billingService->donationPaid($donation);
            if ($result->failed()) {
                return standard_response()->error(
                    'Ошибка при оплате цветка.'
                );
            }

            $donationService->completeDonation($donation);
        }

        $latestFlower = $flowerRepo->getLatestFromDonation($donation);

        if ($latestFlower === null) {
            return standard_response()->success(
                'Цветок не найден.',
                route('front.garden', $donation->garden->slug)
            );
        }

        $successUrl = route('front.garden', [$donation->garden->slug, 'flower' => $latestFlower->hash_id]);

        Cookie::queue($donationService->createPostDonationCookie($donation));

        return standard_response()->success(
            'Ваши цветы успешно посажены.',
            $successUrl,
            [
                'redirect' => $successUrl
            ]
        );
    }
}
