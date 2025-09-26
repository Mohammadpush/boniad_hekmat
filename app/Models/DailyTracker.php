<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTracker extends Model
{
            protected $fillable = [
        'request_id',
        'start_date',
        'max_days'
    ];

    protected $casts = [
        'start_date' => 'date',
        'max_days' => 'integer',
    ];
        public function Request()
    {
        return $this->belongsTo(Request::class);
    }
}
