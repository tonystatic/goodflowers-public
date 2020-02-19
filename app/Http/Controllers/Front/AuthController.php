<?php

declare(strict_types=1);

namespace App\Http\Controllers\Front;

use App\Events\DonationSigned;
use App\Repos\DonationRepo;
use App\Repos\FlowerRepo;
use App\Repos\GardenRepo;
use App\Services\DonationService;
use App\Services\SocialAuthService;
use Cookie;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function logout(Request $request)
    {
        if (auth(GUARD_FRONT)->check()) {
            auth(GUARD_FRONT)->logout();
        }

        if ($request->has('to')) {
            return standard_response()->success(null, $request->input('to'));
        }

        return standard_response()->success();
    }

    /**
     * @param string $gardenSlug
     * @param \Illuminate\Http\Request $request
     * @param \App\Repos\GardenRepo $gardenRepo
     * @param \App\Services\DonationService $donationService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function refuse(
        string $gardenSlug,
        Request $request,
        GardenRepo $gardenRepo,
        DonationService $donationService
    ) {
        $garden = $gardenRepo->findBySlug($gardenSlug);
        if ($garden === null) {
            return standard_response()
                ->warning(false, 'Сад не найден.', null);
        }

        Cookie::queue($donationService->deletePostDonationCookie($garden));

        if ($request->has('to')) {
            return standard_response()->success(null, $request->input('to'));
        }

        return standard_response()
            ->success(null, route('front.garden', $garden->slug));
    }

    /**
     * @param string $gardenSlug
     * @param string $provider
     * @param \Illuminate\Http\Request $request
     * @param \App\Services\SocialAuthService $authService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function socialRedirect(
        string $gardenSlug,
        string $provider,
        Request $request,
        SocialAuthService $authService
    ) {
        $redirect = $authService->getRedirect(
            $provider,
            \array_merge($request->all(), ['garden_slug' => $gardenSlug])
        );
        if ($redirect === null) {
            return standard_response()->error();
        }

        return $redirect;
    }

    /**
     * @param string $provider
     * @param \Illuminate\Http\Request $request
     * @param \App\Repos\GardenRepo $gardenRepo
     * @param \App\Services\SocialAuthService $authService
     * @param \App\Services\DonationService $donationService
     * @param \App\Repos\DonationRepo $donationRepo
     * @param FlowerRepo $flowerRepo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function socialCallback(
        string $provider,
        Request $request,
        GardenRepo $gardenRepo,
        SocialAuthService $authService,
        DonationService $donationService,
        DonationRepo $donationRepo,
        FlowerRepo $flowerRepo
    ) {
        $authService->extractParameters($provider, $request);

        if (! $request->has('garden_slug')) {
            return standard_response()
                ->warning(false, null, route('front.index'));
        }

        $garden = $gardenRepo->findBySlug($request->input('garden_slug'));
        if ($garden === null) {
            return standard_response()
                ->warning(false, null, route('front.index'));
        }

        $donation = $donationService->extractDonationFromCookie($garden, $request);
        if ($donation === null) {
            return standard_response()
                ->error('Не удалось подписать цветок.', route('front.garden', $garden->slug));
        }
        $latestFlower = $flowerRepo->getLatestFromDonation($donation);
        $successUrl = $request->has('to')
            ? $request->input('to')
            : $latestFlower !== null
                ? route('front.garden', [$garden->slug, 'flower' => $latestFlower->hash_id])
                : route('front.garden', $garden->slug);

        $social = $authService->getSocialOnCallback($provider, false);
        if ($social === null) {
            return standard_response()
                ->error(
                    'Ошибка социального логина.',
                    $successUrl
                );
        }

        $user = $authService->findOrRegisterUserBySocial($social);

        auth(GUARD_FRONT)->login($user, true);

        $donationRepo->setUser($donation, $user);

        event(new DonationSigned($donation));

        Cookie::queue($donationService->deletePostDonationCookie($garden));

        return standard_response()
            ->success(
                'Цветок успешно подписан!',
                $successUrl
            );
    }
}
