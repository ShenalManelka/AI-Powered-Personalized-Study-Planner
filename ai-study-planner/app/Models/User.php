<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentProfile() { return $this->hasOne(StudentProfile::class); }
    public function subjects() { return $this->hasMany(Subject::class); }
    public function assignments() { return $this->hasMany(Assignment::class); }
    public function exams() { return $this->hasMany(Exam::class); }
    public function studyAvailabilities() { return $this->hasMany(StudyAvailability::class); }
    public function predictions() { return $this->hasMany(Prediction::class); }
    public function recommendations() { return $this->hasMany(Recommendation::class); }
    public function studyPlans()
    {
        return $this->hasMany(StudyPlan::class);
    }

    public function smartAlerts()
    {
        return $this->hasMany(SmartAlert::class);
    }
}
