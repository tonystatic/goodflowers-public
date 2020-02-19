<?php

/*
|--------------------------------------------------------------------------
| Service Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Service'], function () {

    Route::group(['prefix' => 'service'], function () {

        Route::group(['prefix' => 'billing'], function () {

            Route::post('paid',   'BillingController@paid'  )->name('service.billing.paid');
            Route::post('failed', 'BillingController@failed')->name('service.billing.failed');
        });
    });

    Route::get('robots.txt', 'MainController@robots')->name('service.robots');
});
