<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripePayment extends Model
{
    protected $fillable = [
        'payment_intent_id',
        'status',
        'amount',
        'currency',
        'metadata',
        'applied_promotion_code',
        'applied_coupon_id',
        'applied_coupon_code',
        'discount_amount',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}


