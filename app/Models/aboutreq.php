<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class aboutreq extends Model
{
        protected $fillable = [
        'request_id',
        'about',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }
}

