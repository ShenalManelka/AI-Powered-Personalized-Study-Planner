<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\AIService;

class PredictionController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display the AI Predictions page.
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->studentProfile;

        // Get upcoming exams with their latest predictions
        $upcomingExams = $user->exams()
            ->where('status', 'upcoming')
            ->with(['predictions' => function ($query) {
                $query->latest();
            }])
            ->orderBy('exam_date', 'asc')
            ->get();

        return view(
            'predictions.index',
            compact('profile', 'upcomingExams')
        );
    }

    /**
     * Run complete AI analysis for all upcoming exams.
     */
    public function analyze(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Get student's academic profile
        |--------------------------------------------------------------------------
        */

        $profile = $user->studentProfile;

        if (!$profile) {
            return redirect()
                ->route('student-profile.edit')
                ->with(
                    'error',
                    'Please complete your academic profile before running AI analysis.'
                );
        }

        // Validate global profile fields
        $globalInputs = [
            'study_hours' => $profile->study_hours,
            'attendance' => $profile->attendance,
            'sleep_hours' => $profile->sleep_hours,
            'internet_usage' => $profile->internet_usage,
        ];

        foreach ($globalInputs as $field => $value) {
            if ($value === null || $value === '') {
                return back()->with(
                    'error',
                    'Please complete all academic information before running AI analysis.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fetch Upcoming Exams
        |--------------------------------------------------------------------------
        */
        $upcomingExams = $user->exams()->where('status', 'upcoming')->get();

        if ($upcomingExams->isEmpty()) {
            return back()->with('error', 'You have no upcoming exams to analyze.');
        }

        $academicService = app(\App\Services\AcademicDataService::class);
        $successCount = 0;

        foreach ($upcomingExams as $exam) {
            
            // Calculate per-subject data
            $assignmentCount = $academicService->getAssignmentCount($user, $exam->subject_id);
            $previousScore = $academicService->getPreviousScore($user, $exam->subject_id);

            // Fallback for previous score if none exists
            if ($previousScore === null) {
                $previousScore = 50.0; // Baseline assumption
            }

            $inputData = [
                'study_hours' => (float) $profile->study_hours,
                'attendance' => (float) $profile->attendance,
                'sleep_hours' => (float) $profile->sleep_hours,
                'internet_usage' => (float) $profile->internet_usage,
                'assignments_completed' => (int) $assignmentCount,
                'previous_score' => (float) $previousScore,
            ];

            // AI Analysis
            $aiResult = $this->aiService->getRecommendations($inputData);

            if ($aiResult === null || !isset($aiResult['predicted_exam_score'], $aiResult['academic_risk'], $aiResult['cluster'], $aiResult['cluster_label'])) {
                continue; // Skip this exam if API fails
            }

            $predictedScore = $aiResult['predicted_exam_score'];
            $academicRisk = $aiResult['academic_risk'];
            $clusterData = [
                'cluster' => $aiResult['cluster'],
                'cluster_label' => $aiResult['cluster_label']
            ];
            
            $recommendations = $aiResult['recommendations'] ?? [];
            
            $rawPriority = strtolower($aiResult['priority'] ?? 'low');
            if (str_contains($rawPriority, 'high') || str_contains($rawPriority, 'critical') || str_contains($rawPriority, 'severe')) {
                $priority = 'high';
            } elseif (str_contains($rawPriority, 'medium') || str_contains($rawPriority, 'normal')) {
                $priority = 'medium';
            } else {
                $priority = 'low';
            }

            try {
                DB::transaction(function () use (
                    $user, $exam, $predictedScore, $academicRisk, $clusterData, $recommendations, $priority
                ) {
                    // Save Prediction linked to Exam
                    $prediction = $user->predictions()->create([
                        'exam_id' => $exam->id,
                        'predicted_exam_score' => $predictedScore,
                        'academic_risk' => $academicRisk,
                        'cluster' => $clusterData['cluster'],
                        'cluster_label' => $clusterData['cluster_label'],
                        'prediction_date' => now(),
                    ]);

                    // Save Recommendations
                    foreach ($recommendations as $recommendation) {
                        $recommendationText = is_string($recommendation) ? $recommendation : json_encode($recommendation);
                        $user->recommendations()->create([
                            'prediction_id' => $prediction->id,
                            'priority' => $priority,
                            'recommendation_text' => $recommendationText,
                            'is_completed' => false,
                        ]);
                    }
                });
                
                $successCount++;
                
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($successCount > 0) {
            return redirect()->route('predictions.index')->with('status', 'AI Analysis completed for ' . $successCount . ' exams!');
        } else {
            return back()->with('error', 'AI analysis failed for your exams. Please ensure the ML API is running.');
        }
    }
}