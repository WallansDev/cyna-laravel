<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Carousel
 *
 * @property $id
 * @property $title
 * @property $image_path
 * @property $description
 * @property $link
 * @property $position
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Carousel extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'image_path', 'description', 'link', 'position'];

    public function getImageUrlAttribute()
    {
        return asset('storage/projects/' . $this->image_path);
    }
}
