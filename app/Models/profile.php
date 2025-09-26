<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class profile extends Model
{

       protected $fillable = [
        'user_id',
        'name',
        'nationalcode',
        'position',
        'imgpath',
        'phone',
    ];

    // هیچ cast خاصی نیاز نیست - همه string هستند

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarships()
    {
        return $this->hasMany(scholarship::class);
    }

}
