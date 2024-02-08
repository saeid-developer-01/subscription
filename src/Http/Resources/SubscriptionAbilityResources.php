<?php

namespace IICN\Subscription\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionAbilityResources extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'type' => $this->type,
            'count' => $this->count,
            'subscription_id' => $this->subscription_id,
            'description' => $this->description,
            'subscription' => SubscriptionResources::make($this->whenLoaded('subscription')),
        ];
    }
}
