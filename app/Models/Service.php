<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable =
    [
        'name',
        'description',
        'image_path',
        'position',
        'availbility',
        'top_position',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
