<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyPlanItem extends Model
{
    //

    protected $fillable = ['study_plan_id', 'subject_id', 'assignment_id', 'title', 'study_date', 'start_time', 'end_time', 'duration_minutes', 'status'];
    public function studyPlan() { return $this->belongsTo(StudyPlan::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function assignment() { return $this->belongsTo(Assignment::class); }
}

