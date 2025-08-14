<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    // Activer les timestamps
    public $timestamps = true;
    
    protected $fillable = ['address_line1', 'address_line2', 'city', 'postal_code', 'country', 'phone', 'user_id'];

    /**
     * Obtenir la date de création formatée
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : 'Non disponible';
    }

    /**
     * Obtenir la date de modification formatée
     */
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : 'Non disponible';
    }

    /**
     * Vérifier si l'adresse a été créée récemment (dans les 24h)
     */
    public function isRecentlyCreated()
    {
        if (!$this->created_at) {
            return false;
        }
        return $this->created_at->isAfter(now()->subDay());
    }

    /**
     * Vérifier si l'adresse a été modifiée récemment (dans les 24h)
     */
    public function isRecentlyUpdated()
    {
        if (!$this->updated_at) {
            return false;
        }
        return $this->updated_at->isAfter(now()->subDay());
    }

    /**
     * Obtenir l'adresse complète formatée
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        
        $address .= ', ' . $this->postal_code . ' ' . $this->city;
        
        if ($this->country) {
            $address .= ', ' . $this->country;
        }
        
        return $address;
    }

    /**
     * Méthode publique pour obtenir la date de création formatée
     */
    public function getCreatedAtFormatted()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : 'Non disponible';
    }

    /**
     * Méthode publique pour obtenir la date de modification formatée
     */
    public function getUpdatedAtFormatted()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : 'Non disponible';
    }

    /**
     * Méthode publique pour obtenir l'adresse complète
     */
    public function getFullAddress()
    {
        $address = $this->address_line1;
        
        if ($this->address_line2) {
            $address .= ', ' . $this->address_line2;
        }
        
        $address .= ', ' . $this->postal_code . ' ' . $this->city;
        
        if ($this->country) {
            $address .= ', ' . $this->country;
        }
        
        return $address;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
