<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =
    [
        'name',
        'description',
        'image_path',
        'position',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
