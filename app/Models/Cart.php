<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'services_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id');
    }

    // Accesseurs
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Méthodes statiques utiles
    public static function getCartTotal($userId)
    {
        return static::where('user_id', $userId)
            ->get()
            ->sum('subtotal');
    }

    public static function getCartCount($userId)
    {
        return static::where('user_id', $userId)
            ->sum('quantity');
    }
}