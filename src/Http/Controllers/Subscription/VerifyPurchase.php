<?php

namespace IICN\Subscription\Http\Controllers\Subscription;

use IICN\Subscription\Http\Controllers\Controller;
use IICN\Subscription\Http\Requests\VerifyPurchaseRequest;
use IICN\Subscription\Models\Subscription;
use IICN\Subscription\Services\Purchase\Appstore;
use IICN\Subscription\Services\Purchase\Playstore;
use IICN\Subscription\Services\Purchase\Purchase;
use IICN\Subscription\Services\Response\SubscriptionResponse;

class VerifyPurchase extends Controller
{
    public function __invoke(Subscription $subscription, VerifyPurchaseRequest $request)
    {
        if ($request->isAppstore) {
            $playstore = new Purchase(new Appstore());
        } else {
            $playstore = new Purchase(new Playstore());
        }

        $result = $playstore->verify($subscription->sku_code, $request->purchaseToken);

        if ($result['status']) {
            return SubscriptionResponse::success();
        } else {
            return SubscriptionResponse::error();
        }
    }
}
