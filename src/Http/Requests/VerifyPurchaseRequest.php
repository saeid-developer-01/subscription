<?php

namespace IICN\Subscription\Http\Requests;

use Carbon\Carbon;
use IICN\Subscription\CouponConditionInterface;
use IICN\Subscription\Models\SubscriptionCoupon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class VerifyPurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::guard(config('subscription.guard'))->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'isAppstore' => 'required|boolean',
            'purchaseToken' => 'required|string|max:200',
        ];
    }
}
