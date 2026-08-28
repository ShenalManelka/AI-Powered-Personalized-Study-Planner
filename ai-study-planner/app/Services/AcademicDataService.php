<?php
namespace App\Services;

use App\Models\User;

class AcademicDataService
{
    /**
     * Get the most recent completed exam score for the given user and (optional) subject.
     * Returns null if no exam is found.
     */
    public function getPreviousScore(User $user, $subjectId = null)
    {
        $examQuery = $user->exams()
            ->whereNotNull('score')
            ->where('status', 'completed');

        if ($subjectId) {
            $examQuery->where('subject_id', $subjectId);
        }

        $exam = $examQuery->orderByDesc('exam_date')->first();

        return $exam ? (float) $exam->score : null;
    }

    /**
     * Calculate assignment count for a specific subject for the ML model (max 10).
     * Returns an integer between 0 and 10.
     */
    public function getAssignmentCount(User $user, $subjectId = null)
    {
        $query = $user->assignments()->where('status', 'completed');
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        $completed = $query->count();

        // The ML model expects a count from 0-10
        return min(10, $completed);
    }
}
?>
