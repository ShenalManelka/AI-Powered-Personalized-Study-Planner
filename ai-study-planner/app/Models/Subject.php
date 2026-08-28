<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //

    protected $fillable = ['user_id', 'name', 'code', 'description'];
    public function user() { return $this->belongsTo(User::class); }
    public function assignments() { return $this->hasMany(Assignment::class); }
    public function exams() { return $this->hasMany(Exam::class); }
    public function studyPlanItems() { return $this->hasMany(StudyPlanItem::class); }
}

