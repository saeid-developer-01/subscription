<?php

Route::prefix('subscription/api/v1')->group(function () {
    Route::namespace("IICN\Subscription\Http\Controllers")->group(function () {
        Route::namespace('Subscription')->group(function () {
            Route::get('subscriptions', 'Index');
            Route::get('subscriptions/types/{type}', 'IndexByType');
            Route::post('subscriptions/{subscription}/verify-purchase', 'VerifyPurchase');
        });

        Route::namespace('SubscriptionCoupon')->group(function () {
            Route::post('subscription-coupons', 'StoreWithSubscriptionCoupon');
        });
        Route::namespace('Test')->group(function () {
            Route::get('test', 'Test');
        });
    });
});
