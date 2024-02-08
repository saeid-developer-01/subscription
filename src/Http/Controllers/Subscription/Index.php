<?php

namespace IICN\Subscription\Http\Controllers\Subscription;

use IICN\Subscription\Http\Controllers\Controller;
use IICN\Subscription\Http\Resources\SubscriptionResources;
use IICN\Subscription\Models\Subscription;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class Index extends Controller
{
    public function __invoke(): AnonymousResourceCollection
    {
        $subscriptions = Subscription::query()->get();

        return SubscriptionResources::collection($subscriptions);
    }
}
