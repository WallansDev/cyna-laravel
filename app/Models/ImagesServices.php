<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagesServices extends Model
{
    protected $table = 'images_services';
    protected $fillable = ['service_id', 'image_path'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
