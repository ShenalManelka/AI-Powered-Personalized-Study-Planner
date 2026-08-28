<?php
$user = App\Models\User::where('email', 'demo@test.com')->first();
if (!$user) { echo "User not found\n"; exit; }

// Delete existing exams and subjects for a clean test
$user->exams()->delete();
$user->assignments()->delete();
$user->subjects()->delete();

// Step 1: Create a Brand New Subject
$subject = $user->subjects()->create([
    'name' => 'Database Systems',
    'code' => 'CS301',
    'color' => '#38c172'
]);

// Step 2: Create an Upcoming Exam
// We specifically do NOT create any past exams for this subject
$user->exams()->create([
    'subject_id' => $subject->id,
    'title' => 'Final Exam',
    'exam_date' => now()->addDays(20)->format('Y-m-d'),
    'exam_type' => 'Final',
    'status' => 'upcoming',
    'score' => null
]);

echo "Test data for PRED-03 seeded successfully for demo@test.com\n";
