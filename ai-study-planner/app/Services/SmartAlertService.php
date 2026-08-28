<?php

namespace App\Services;

use App\Models\User;
use App\Models\SmartAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SmartAlertService
{
    /**
     * Synchronize and generate all necessary alerts for the given user.
     */
    public function syncUserAlerts(User $user)
    {
        Log::info("Syncing Smart Alerts for User {$user->id}");

        $this->checkHighRiskPrediction($user);
        $this->checkImpendingAssignments($user);
        $this->checkApproachingExams($user);
        $this->checkMissedStudySessions($user);
    }

    /**
     * Check if the latest prediction shows high academic risk.
     */
    private function checkHighRiskPrediction(User $user)
    {
        $prediction = $user->predictions()->latest()->first();

        if ($prediction && strtolower($prediction->academic_risk) === 'high') {
            $this->createAlertIfNotExists(
                $user,
                'high_risk',
                "Warning: Your latest AI analysis indicates a High Academic Risk. Please review your study plan.",
                route('predictions.index'),
                $prediction
            );
        }
    }

    /**
     * Check for assignments due within the next 48 hours.
     */
    private function checkImpendingAssignments(User $user)
    {
        $assignments = $user->assignments()
            ->where('status', '!=', 'completed')
            ->where('deadline', '<=', now()->addHours(48))
            ->where('deadline', '>=', now())
            ->get();

        foreach ($assignments as $assignment) {
            $this->createAlertIfNotExists(
                $user,
                'assignment_due',
                "Urgent: Assignment '{$assignment->title}' is due soon!",
                route('assignments.index'),
                $assignment
            );
        }
    }

    /**
     * Check for exams approaching within the next 3 days.
     */
    private function checkApproachingExams(User $user)
    {
        $exams = $user->exams()
            ->whereDate('exam_date', '<=', now()->addDays(3))
            ->whereDate('exam_date', '>=', today())
            ->get();

        foreach ($exams as $exam) {
            $this->createAlertIfNotExists(
                $user,
                'exam_approaching',
                "Reminder: Exam '{$exam->title}' is approaching on " . Carbon::parse($exam->exam_date)->format('M d, Y') . ".",
                route('dashboard'),
                $exam
            );
        }
    }

    /**
     * Check for study sessions marked as missed, or passed sessions that are still pending.
     */
    private function checkMissedStudySessions(User $user)
    {
        $activePlan = $user->studyPlans()->where('status', 'active')->first();
        if (!$activePlan) return;

        // Auto-mark past pending sessions as missed before checking
        $activePlan->studyPlanItems()
            ->where('status', 'pending')
            ->where(function($q) {
                $q->whereDate('study_date', '<', today())
                  ->orWhere(function($q2) {
                      $q2->whereDate('study_date', today())
                         ->whereTime('end_time', '<', now());
                  });
            })
            ->update(['status' => 'missed']);

        // Generate alerts for recent missed sessions (last 3 days to avoid spam)
        $missedSessions = $activePlan->studyPlanItems()
            ->where('status', 'missed')
            ->whereDate('study_date', '>=', today()->subDays(3))
            ->get();

        foreach ($missedSessions as $session) {
            $this->createAlertIfNotExists(
                $user,
                'missed_session',
                "You missed a scheduled study session: '{$session->title}'. Consider rescheduling.",
                route('study-plans.index'),
                $session
            );
        }
    }

    /**
     * Helper to create an alert idempotently (prevents duplicate spam).
     */
    private function createAlertIfNotExists(User $user, $type, $message, $actionUrl, $relatedModel)
    {
        // Check if an alert already exists for this specific model and type
        $exists = SmartAlert::where('user_id', $user->id)
            ->where('type', $type)
            ->where('related_model_type', get_class($relatedModel))
            ->where('related_model_id', $relatedModel->id)
            ->exists();

        if (!$exists) {
            SmartAlert::create([
                'user_id' => $user->id,
                'type' => $type,
                'message' => $message,
                'action_url' => $actionUrl,
                'related_model_type' => get_class($relatedModel),
                'related_model_id' => $relatedModel->id,
                'is_read' => false,
            ]);
        }
    }
}
