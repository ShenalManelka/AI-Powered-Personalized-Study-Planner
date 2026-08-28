<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    //

    protected $fillable = ['user_id', 'subject_id', 'title', 'exam_date', 'exam_type', 'status', 'score'];
    public function user() { return $this->belongsTo(User::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function predictions() { return $this->hasMany(Prediction::class); }
}

