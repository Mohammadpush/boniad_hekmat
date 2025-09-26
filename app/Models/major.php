<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class major extends Model
{
    protected $fillable = [
        'name'
    ];

    // رابطه یک به چند با requests
    public function requests()
    {
        return $this->hasOne(Request::class);
    }
}
