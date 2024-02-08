<?php

namespace IICN\Subscription\Services\Purchase;

use IICN\Subscription\Models\Subscription;
use IICN\Subscription\Models\SubscriptionTransaction;
use IICN\Subscription\Services\Transaction\TransactionalData;
use Illuminate\Support\Facades\Auth;

class Purchase
{
    public function __construct(protected HasVerifyPurchase $purchaseClass)
    {

    }

    public function verify(string $productId, string $purchaseToken): array
    {
        $subscription = Subscription::query()->where('sku_code', $productId)->first();

        $additionalDataClass = config('subscription.additional_data_transaction');

        $additionalData = [];

        if (app($additionalDataClass) instanceof TransactionalData) {
             $transactionalData = new $additionalDataClass();
             $additionalData = $transactionalData->additionalData();
        }

        $transaction = SubscriptionTransaction::query()->create([
            'user_id' => Auth::guard(config('subscription.guard'))->id(),
            'subscription_id' => $subscription->id ?? null,
            'agent_type' => $this->purchaseClass->getAgentType(),
            'purchase_token' => $purchaseToken,
            'product_id' => $productId,
            'additional_data' => $additionalData,
        ]);

        return $this->purchaseClass->verifyPurchase($transaction);
    }
}
