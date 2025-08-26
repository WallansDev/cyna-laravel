<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        // Ajoute d'autres champs si besoin
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
