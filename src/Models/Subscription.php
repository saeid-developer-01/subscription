<?php

namespace IICN\Subscription\Models;

use IICN\Subscription\Traits\HasTranslateFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes, HasTranslateFields;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'duration_day',
        'price',
        'discount_percent',
        'sku_code',
        'type',
        'count',
        'description',
    ];

    protected $casts = [
        'title' => 'json',
        'description' => 'json',
    ];

    public static array $translateFields = ['title', 'description'];

    public function subscriptionAbilities(): HasMany
    {
        return $this->hasMany(SubscriptionAbility::class);
    }
}
