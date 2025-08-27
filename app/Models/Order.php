<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'billing_address_id',
        'stripe_payment_id',
        // Ajoute d'autres champs si besoin
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function billingAddress()
    {
        return $this->belongsTo(\App\Models\BillingAddress::class);
    }

    public function stripePayment()
    {
        return $this->belongsTo(\App\Models\StripePayment::class);
    }
}
