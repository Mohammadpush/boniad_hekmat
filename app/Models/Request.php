<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'female',
        'birthdate',
        'nationalcode',
        'phone',
        'telephone',
        'rental',
        'grade',
        'major_id',
        'school',
        'last_score',
        'principal',
        'school_telephone',
        'father_name',
        'father_phone',
        'father_job',
        'mother_name',
        'mother_phone',
        'mother_job',
        'address',
        'father_job_address',
        'mother_job_address',
        'father_income',
        'mother_income',
        'siblings_count',
        'siblings_rank',
        'english_proficiency',
        'know',
        'counseling_method',
        'why_counseling_method',
        'motivation',
        'spend',
        'how_am_i',
        'favorite_major',
        'future',
        'help_others',
        'suggestion',
        'imgpath',
        'gradesheetpath',
        'story',
        'date',
        'cardnumber'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'rental' => 'boolean',
        'date' => 'datetime',
        'english_proficiency' => 'integer',
        'father_income' => 'integer',
        'mother_income' => 'integer',
        'siblings_count' => 'integer',
        'siblings_rank' => 'integer',
        'last_score' => 'integer'
    ];

    // روابط
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function scholarships()
    {
        return $this->hasMany(scholarship::class);
    }

    public function aboutreqs()
    {
        return $this->hasMany(aboutreq::class);
    }

    public function DailyTracker()
    {
        return $this->hasOne(DailyTracker::class);
    }
}
