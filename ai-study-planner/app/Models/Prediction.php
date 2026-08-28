<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    //

    protected $fillable = ['user_id', 'exam_id', 'predicted_exam_score', 'academic_risk', 'cluster', 'cluster_label', 'prediction_date'];
    public function user() { return $this->belongsTo(User::class); }
    public function exam() { return $this->belongsTo(Exam::class); }
    public function recommendations() { return $this->hasMany(Recommendation::class); }
}

