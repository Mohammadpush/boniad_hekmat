<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\profile;

class scholarship extends Model
{
       protected $fillable = [
        'profile_id',
        'request_id',
        'sender_user_id',
        'description',
        'price',
        'story',
        'tick',
        'ismaster',
    ];

    protected $casts = [
        'price' => 'integer',
        'tick' => 'boolean',
        'ismaster' => 'boolean',
    ];

    public function profile(){
        return $this->belongsTo(profile::class)->withDefault();
    }

    public function request(){
        return $this->belongsTo(Request::class);
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sender_user_id');
    }
}
