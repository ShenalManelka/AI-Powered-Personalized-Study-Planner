<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    //

    protected $fillable = ['user_id', 'study_hours', 'attendance', 'sleep_hours', 'internet_usage', 'assignments_completed', 'previous_score'];
    public function user() { return $this->belongsTo(User::class); }
}

