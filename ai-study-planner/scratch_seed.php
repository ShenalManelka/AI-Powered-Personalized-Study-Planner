<?php
$user = App\Models\User::where('email', 'test@test.com')->first();
if (!$user) { echo "User not found\n"; exit; }

$subject = $user->subjects()->first();
if (!$subject) {
    $subject = $user->subjects()->create([
        'name' => 'Data Science',
        'color' => '#3490dc'
    ]);
}

$user->assignments()->create([
    'subject_id' => $subject->id,
    'title' => 'Data Analysis Project',
    'deadline' => now()->addDays(5)->format('Y-m-d'),
    'status' => 'pending'
]);

$user->assignments()->create([
    'subject_id' => $subject->id,
    'title' => 'Machine Learning HW 1',
    'deadline' => now()->subDays(2)->format('Y-m-d'),
    'status' => 'completed'
]);

$user->studyAvailabilities()->create([
    'day_of_week' => 1,
    'start_time' => '14:00:00',
    'end_time' => '16:00:00',
    'available_hours' => 2.0
]);

$user->studyAvailabilities()->create([
    'day_of_week' => 3,
    'start_time' => '10:00:00',
    'end_time' => '12:00:00',
    'available_hours' => 2.0
]);

echo "Data seeded successfully for test@test.com\n";
