<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //

    protected $fillable = ['user_id', 'subject_id', 'title', 'description', 'deadline', 'priority', 'status', 'estimated_hours'];
    public function user() { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function studyPlanItems() { return $this->hasMany(StudyPlanItem::class); }
}

