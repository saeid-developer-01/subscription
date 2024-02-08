<?php

namespace IICN\Subscription\Services;

use Carbon\Carbon;
use IICN\Subscription\HasSubscription;
use IICN\Subscription\Models\SubscriptionUser;

class Subscription
{

    public function __construct(protected HasSubscription $model)
    {
    }

    public function create(int $subscription_id, ?int $durationDay = null, ?array $remainingNumber = null): bool|int
    {
        $subscription = \IICN\Subscription\Models\Subscription::query()->find($subscription_id);

        if (isset($subscription->id)) {

            $durationDay = $durationDay ?: $subscription->duration_day;

            if ($durationDay !== -1) {
                $expiryAt = Carbon::now()->addDays($durationDay);
            } else {
                $expiryAt = null;
            }

            if ($remainingNumber === null) {
                $remainingNumber = [];
                foreach ($subscription->subscriptionAbilities as $ability) {
                    $remainingNumber[$ability->type] = $ability->count;
                }
            }

            $this->model->subscriptions()->attach($subscription_id, ['expiry_at' => $expiryAt, 'remaining_number' => $remainingNumber]);

            $subscriptionUser = SubscriptionUser::query()->where('user_id', $this->model->id)->where('subscription_id', $subscription_id)->latest('id')->first();

            return $subscriptionUser->id;
        }

        return false;
    }

    public function used(string $type): bool
    {
        $subscription = $this->getActiveWithCount($type);

        if (! isset($subscription[0]) ) {
            return false;
        }

        $data = $subscription[0]->pivot->remaining_number;

        $data[$type] = ((int) ($data[$type]) - 1);

        $subscription[0]->pivot->remaining_number = $data;

        if ($data[$type] == 0 and $subscription[0]->pivot->expiry_at == null) {
            $subscription[0]->pivot->expiry_at = Carbon::now();
        }

        $subscription[0]->pivot->save();

        return true;
    }

    public function getActiveWithCount(string $type): array
    {
        $subscriptions = $this->model->activeSubscriptionsWithType($type)->get();

        $activeSubscriptions = [];
        $hasExpiryAt = false;

        foreach ($subscriptions as $subscription) {
            if (isset($subscription->pivot->remaining_number[$type]) and (int) $subscription->pivot->remaining_number[$type] > 0) {
                $activeSubscriptions[] = $subscription;
            }
            if ($subscription->pivot->expiry_at) {
                $hasExpiryAt = true;
            }
        }

        return $hasExpiryAt ? $activeSubscriptions : [];
    }


    public function canUse(string $type): bool
    {
        return isset($this->getActiveWithCount($type)[0]);
    }

    public function getSubscriptionTypes(): array
    {
        return array_unique($this->model->activeSubscriptions->pluck('type')->toArray());
    }

    public function getSubscriptionableId(): int|null|string
    {
        return $this->model->id;
    }
}
