<?php

namespace IICN\Subscription\Concerns;

use Carbon\Carbon;
use IICN\Subscription\Models\SubscriptionUser;
use IICN\Subscription\Services\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ManagesSubscriptions
{
    public function newSubscription(int $subscription_id, ?int $durationDay = null, ?array $remainingNumber = null): bool
    {
        $subscription = new Subscription($this);

        return $subscription->create($subscription_id, $durationDay, $remainingNumber);
    }

    public function useSubscription(string $type): bool
    {
        $subscription = new Subscription($this);

        return $subscription->used($type);
    }

    public function getActiveSubscriptionsWithTypeAndHasCount(string $type): array
    {
        $subscription = new Subscription($this);

        return $subscription->getActiveWithCount($type);
    }

    public function getSubscriptionTypes(): array
    {
        $subscription = new Subscription($this);

        return $subscription->getSubscriptionTypes();
    }

    public function activeSubscriptionsWithType(string $type): BelongsToMany
    {
        return $this->activeSubscriptions()->whereHas('subscriptionAbilities', function ($query) use ($type) {
            $query->where('type', $type);
        });
    }

    public function activeSubscriptions(): BelongsToMany
    {
        return $this->subscriptions()->wherePivot('expiry_at', '>', Carbon::now())->orWherePivotNull('expiry_at');
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(\IICN\Subscription\Models\Subscription::class, SubscriptionUser::class, 'user_id', 'subscription_id')
            ->withPivot(['expiry_at', 'remaining_number', 'id'])->withTimestamps()
            ->orderBy('pivot_created_at');
    }
}
