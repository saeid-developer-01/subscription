<?php

namespace IICN\Subscription\Http\Controllers\SubscriptionCoupon;

use IICN\Subscription\Constants\Status;
use IICN\Subscription\Http\Controllers\Controller;
use IICN\Subscription\Http\Requests\StoreWithSubscriptionCouponRequest;
use IICN\Subscription\Models\SubscriptionCoupon;
use IICN\Subscription\Models\SubscriptionUser;
use IICN\Subscription\Services\Response\SubscriptionResponse;
use IICN\Subscription\Subscription;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StoreWithSubscriptionCoupon extends Controller
{
    public function __invoke(StoreWithSubscriptionCouponRequest $request)
    {
        $subscriptionCoupon = SubscriptionCoupon::query()->find($request->validated('subscription_coupon_id'));

        DB::beginTransaction();

        $result = Subscription::create($subscriptionCoupon->subscription_id, $subscriptionCoupon->duration_day);

        if (!$result) {
            DB::rollBack();
            return SubscriptionResponse::error();
        }

        $result = $subscriptionCoupon->update(['count' => $subscriptionCoupon->count - 1]);

        if (!$result) {
            DB::rollBack();
            return SubscriptionResponse::error();
        }

        DB::commit();

        return SubscriptionResponse::success();
    }
}
