<?php
$user = App\Models\User::firstOrCreate(
    ['email' => 'test@test.com'],
    ['name' => 'Test Student', 'password' => bcrypt('password')]
);

// Profile
$user->studentProfile()->updateOrCreate(
    ['user_id' => $user->id],
    ['study_hours' => 25.5, 'attendance' => 88.0, 'sleep_hours' => 7.5, 'internet_usage' => 4.0, 'assignments_completed' => 12, 'previous_score' => 78.5]
);

// Subjects
$sub1 = $user->subjects()->firstOrCreate(['name' => 'Data Structures'], ['code' => 'CS101']);
$sub2 = $user->subjects()->firstOrCreate(['name' => 'Software Engineering'], ['code' => 'CS102']);
$sub3 = $user->subjects()->firstOrCreate(['name' => 'Machine Learning'], ['code' => 'CS103']);

// Assignments
$user->assignments()->firstOrCreate(['title' => 'BST Implementation', 'subject_id' => $sub1->id], ['deadline' => now()->addDays(2), 'priority' => 'high']);
$user->assignments()->firstOrCreate(['title' => 'Project Proposal', 'subject_id' => $sub2->id], ['deadline' => now()->addDays(5), 'priority' => 'medium']);
$user->assignments()->firstOrCreate(['title' => 'Neural Networks lab', 'subject_id' => $sub3->id], ['deadline' => now()->addDays(1), 'priority' => 'high']);

// Exams
$user->exams()->firstOrCreate(['title' => 'Midterm Exam', 'subject_id' => $sub3->id], ['exam_date' => now()->addDays(14), 'exam_type' => 'Midterm']);
$user->exams()->firstOrCreate(['title' => 'Final Presentation', 'subject_id' => $sub2->id], ['exam_date' => now()->addDays(30), 'exam_type' => 'Final']);

// Predictions (Just mock data, no recommendations)
$user->predictions()->firstOrCreate(['prediction_date' => now()->toDateString()], [
    'predicted_exam_score' => 82.4,
    'academic_risk' => 'Low',
    'cluster' => 1,
    'cluster_label' => 'Diligent Learner',
    'prediction_date' => now()
]);

// Study Plan
$plan = $user->studyPlans()->firstOrCreate(['status' => 'active']);
$plan->studyPlanItems()->firstOrCreate(['title' => 'Read Chapter 4', 'subject_id' => $sub1->id], ['study_date' => now()->toDateString(), 'start_time' => '14:00:00', 'end_time' => '15:30:00', 'duration_minutes' => 90, 'status' => 'pending']);
$plan->studyPlanItems()->firstOrCreate(['title' => 'Practice algorithms', 'subject_id' => $sub1->id], ['study_date' => now()->toDateString(), 'start_time' => '16:00:00', 'end_time' => '17:00:00', 'duration_minutes' => 60, 'status' => 'completed']);
$plan->studyPlanItems()->firstOrCreate(['title' => 'Design Document', 'subject_id' => $sub2->id], ['study_date' => now()->addDay()->toDateString(), 'start_time' => '09:00:00', 'end_time' => '11:00:00', 'duration_minutes' => 120, 'status' => 'pending']);

echo "Demo data created for test@test.com\n";
