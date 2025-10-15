<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\profile;

class scholarship extends Model
{
       protected $fillable = [
        'profile_id',
        'request_id',
        'description',
        'price',
        'story',
        'ismaster',
    ];

    protected $casts = [
        'price' => 'integer',
        'ismaster' => 'boolean',
    ];
    public function profile(){


        return $this->belongsTo(profile::class)->withDefault();

    }
    public function request(){


        return $this->belongsTo(Request::class);

    }
}
