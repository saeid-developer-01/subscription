<?php

namespace IICN\Subscription\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResources extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'duration_day' => $this->duration_day,
            'price' => $this->price,
            'discount_percent' => $this->discount_percent,
            'sku_code' => $this->sku_code,
            'type' => $this->type,
            'count' => $this->count,
            'description' => $this->description,
            'subscriptionAbilities' => SubscriptionAbilityResources::collection($this->whenLoaded('subscriptionAbilities')),
        ];
    }
}
