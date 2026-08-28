<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'Demo Student',
            'email' => 'demo@student.com',
            'password' => bcrypt('password'),
        ]);

        $user->studentProfile()->create([
            'study_hours' => 12.5,
            'attendance' => 95,
            'sleep_hours' => 7.5,
            'internet_usage' => 4,
            'assignments_completed' => 15,
            'previous_score' => 85,
        ]);

        $subject1 = $user->subjects()->create(['name' => 'Machine Learning', 'code' => 'CS401', 'description' => 'Intro to ML']);
        $subject2 = $user->subjects()->create(['name' => 'Web Development', 'code' => 'CS402', 'description' => 'Advanced web dev']);

        $user->assignments()->create(['subject_id' => $subject1->id, 'title' => 'Clustering Assignment', 'deadline' => now()->addDays(5), 'priority' => 'high']);
        $user->assignments()->create(['subject_id' => $subject2->id, 'title' => 'Laravel Project Phase 1', 'deadline' => now()->addDays(2), 'priority' => 'high']);

        $user->exams()->create(['subject_id' => $subject1->id, 'title' => 'Midterm Exam', 'exam_date' => now()->addDays(14), 'exam_type' => 'Midterm']);
        
        $user->studyAvailabilities()->create(['day_of_week' => 1, 'start_time' => '17:00', 'end_time' => '20:00', 'available_hours' => 3]);
    }
}
