<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'technical_specifications', 'image_path', 'position', 'availbility', 'top_position', 'price_monthly', 'price_yearly'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function gallery()
    {
        return $this->hasMany(ImagesServices::class);
    }
}
