<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $dates = ['updated_at', 'created_at', 'two_factor_expires_at'];

    protected $fillable = ['name', 'surname', 'email', 'password', 'siret', 'phone', 'role', 'is_admin', 'two_factor_code', 'two_factor_expires_at'];

    /**
     * Obtenir la date de création formatée
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : null;
    }

    /**
     * Obtenir la date de modification formatée
     */
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : null;
    }

    /**
     * Vérifier si l'utilisateur a été créé récemment (dans les 24h)
     */
    public function isRecentlyCreated()
    {
        return $this->created_at && $this->created_at->isAfter(now()->subDay());
    }

    /**
     * Vérifier si l'utilisateur a été modifié récemment (dans les 24h)
     */
    public function isRecentlyUpdated()
    {
        return $this->updated_at && $this->updated_at->isAfter(now()->subDay());
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    public function isAdmin()
    {
        return $this->is_admin;
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function billingAddresses()
{
    return $this->hasMany(BillingAddress::class);
}
}
