<?php
$user = App\Models\User::where('email', 'demo@test.com')->first();
if (!$user) { echo "User not found\n"; exit; }

// Delete existing exams and assignments to ensure a clean slate for the test
$user->exams()->delete();
$user->assignments()->delete();
$user->subjects()->delete();

// Step 1: Create Two Subjects
$subjectA = $user->subjects()->create([
    'name' => 'Mathematics',
    'code' => 'MATH101',
    'color' => '#e3342f'
]);

$subjectB = $user->subjects()->create([
    'name' => 'History',
    'code' => 'HIST101',
    'color' => '#f6993f'
]);

// Step 2: Set Up the Assignments
// 3 completed assignments for Subject A
for ($i = 1; $i <= 3; $i++) {
    $user->assignments()->create([
        'subject_id' => $subjectA->id,
        'title' => "Math Assignment {$i}",
        'deadline' => now()->subDays(1)->format('Y-m-d'),
        'status' => 'completed'
    ]);
}
// 0 assignments for Subject B

// Step 3: Create the Upcoming Exams
$user->exams()->create([
    'subject_id' => $subjectA->id,
    'title' => 'Math Midterm',
    'exam_date' => now()->addDays(10)->format('Y-m-d'),
    'exam_type' => 'Midterm',
    'status' => 'upcoming',
    'score' => null
]);

$user->exams()->create([
    'subject_id' => $subjectB->id,
    'title' => 'History Midterm',
    'exam_date' => now()->addDays(12)->format('Y-m-d'),
    'exam_type' => 'Midterm',
    'status' => 'upcoming',
    'score' => null
]);

echo "Test data for PRED-02 seeded successfully for demo@test.com\n";
