<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'service_name',
        'quantity',
        'price',
        'price_type', // <-- doit être présent !
    ];

    public static function createFromCartItems($cartItems, $order)
    {
        foreach ($cartItems as $cartItem) {
            self::create([
                'order_id'     => $order->id,
                'service_name' => $cartItem->service->name,
                'quantity'     => $cartItem->quantity,
                'price'        => $cartItem->price,
                'price_type'   => $cartItem->price_type,
            ]);
        }
    }
}