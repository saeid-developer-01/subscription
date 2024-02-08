<?php

namespace IICN\Subscription\Http\Controllers\Test;

use Carbon\Carbon;
use IICN\Schedule\TaskScheduler;
use IICN\Subscription\Http\Controllers\Controller;
use IICN\Subscription\Services\Response\SubscriptionResponse;
use IICN\Subscription\Subscription;
use Illuminate\Support\Facades\Auth;

class Test extends Controller
{
    public function __invoke()
    {
        return SubscriptionResponse::success();
//        TaskScheduler::inTimezone('Asia/Tehran')->command('className', [])->dateTime("2024-01-29 19:06");
//        TaskScheduler::inAllTimezone()->command('className', [])->dateTime("2024-01-29 19:06");
////        $user->newSubscription(1, 12, ['istikhara' => 20]);
//        return 12;
//        return $carbon->timezone('Asia/Tehran')->toDateTimeLocalString();
//        TaskScheduler::inTimezone('strung')->command('test', []);
//        return TaskScheduler::command('data');
        Auth::loginUsingId(1);
        return Subscription::create(1, []);
//        return Subscription::canUse('istikhara');
        return Subscription::used('istikhara');
        return $user->useSubscription("Istikhara");
        $user->newSubscription(1);
    }
}
