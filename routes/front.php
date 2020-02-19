<?php

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
*/

Route::namespace('Front')->group(function () {

    Route::get('/', 'MainController@index')->name('front.index');

    Route::get('privacy', 'MainController@privacy')->name('front.privacy');
    Route::get('offer',   'MainController@offer'  )->name('front.offer');

    Route::group(['prefix' => 'garden/{slug}'], function () {

        Route::get('flowers-data', 'GardenController@flowersData')->name('front.garden.flowersData');

        Route::post('donate', 'DonationController@donate')->name('front.donate')
            ->middleware('billing.enabled');

        Route::get('/', 'GardenController@garden')->name('front.garden');
    });

    Route::group(['prefix' => 'donation/{hashId}'], function () {

        Route::post('pay', 'DonationController@pay')->name('front.donation.pay')
            ->middleware('billing.enabled');
        Route::post('paid', 'DonationController@paid')->name('front.donation.paid')
            ->middleware('billing.enabled');
    });

    Route::group(['prefix' => 'garden/{slug}'], function () {

        Route::get('auth/refuse', 'AuthController@refuse')->name('front.auth.refuse');

        Route::get('auth/{socialProvider}', 'AuthController@socialRedirect')->name('front.auth');
    });

    Route::any('auth/{socialProvider}/callback', 'AuthController@socialCallback')->name('front.auth.callback');

    Route::get('logout', 'AuthController@logout')->name('front.logout');
});
