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
    public function __invoke(VerifyPurchaseRequest $request)
    {
        if ($request->gateway == 'appStore') {
            $playstore = new Purchase(new Appstore());
        } elseif($request->gateway == 'playStore') {
            $playstore = new Purchase(new Playstore());
        }

        $result = $playstore->verify($request->skuCode, $request->purchaseToken, $request->orderId);
        if($result['status']) {
            $message = trans('subscription.payment_done');
        } elseif ($result['transaction']->status == 'failed') {
            $message = trans('subscription.payment_not_valid');
        } elseif ($result['transaction']->status == 'pending') {
            $message = trans('subscription.payment_pending');
        } else {
            $message = trans('subscription.payment_not_valid');
        }

        return SubscriptionResponse::data(['purchase_status' => $result['transaction']->status], $message);
    }
}
