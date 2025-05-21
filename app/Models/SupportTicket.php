<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'subject', 
        'message', 
        'status', 
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
