<?php

namespace App\Services;

use App\Models\User;
use App\Models\StudyPlan;
use App\Models\StudyPlanItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StudyPlanService
{
    /**
     * Generate a new intelligent personalized study plan for the user.
     */
    public function generatePlan(User $user)
    {
        // 1. Gather Data & Validation
        $availabilities = $user->studyAvailabilities()->get()->keyBy('day_of_week');
        if ($availabilities->isEmpty()) {
            throw new \Exception("Please configure your study availability before generating your personalized study plan.");
        }

        // Cancel old active plans (using existing 'cancelled' enum value)
        $user->studyPlans()->where('status', 'active')->update(['status' => 'cancelled']);

        // Create new plan
        $plan = $user->studyPlans()->create([
            'title' => 'Personalized Study Plan',
            'description' => 'AI-informed study plan based on assignments, exams, availability and academic profile.',
            'start_date' => now(),
            'end_date' => now()->addDays(14),
            'status' => 'active'
        ]);

        $assignments = $user->assignments()->where('status', '!=', 'completed')->orderBy('deadline', 'asc')->get();
        $exams = $user->exams()->whereDate('exam_date', '>=', today())->orderBy('exam_date', 'asc')->get();
        
        Log::info("Study Plan Gen - Found Assignments: " . $assignments->count());
        Log::info("Study Plan Gen - Found Exams: " . $exams->count());
        Log::info("Study Plan Gen - Found Availability Records: " . $availabilities->count());
        
        // Extract AI Profile Modifiers
        $prediction = $user->predictions()->latest()->first();
        $riskLevel = $prediction ? strtolower($prediction->academic_risk) : 'unknown';
        $predictedScore = $prediction ? (float) $prediction->predicted_exam_score : null;

        // Calculate AI Base Modifiers
        $aiRiskModifier = $this->calculateRiskModifier($riskLevel);
        $aiPerformanceModifier = $this->calculatePerformanceModifier($predictedScore);

        $tasks = [];
        $today = now()->startOfDay();

        // 2. Build the Exam Revision Queue
        foreach ($exams as $exam) {
            $examDate = Carbon::parse($exam->exam_date)->startOfDay();
            $daysUntilExam = $today->diffInDays($examDate, false);
            
            // Skip past exams
            if ($daysUntilExam < 0) continue;
            
            // Calculate base exam priority
            $priority = 50; 
            $urgencyReason = 'Exam Upcoming';
            
            if ($daysUntilExam <= 2) { $priority += 60; $urgencyReason = 'Exam in <= 2 days'; }
            elseif ($daysUntilExam <= 7) { $priority += 40; $urgencyReason = 'Exam within 7 days'; }
            elseif ($daysUntilExam <= 14) { $priority += 20; $urgencyReason = 'Exam within 14 days'; }

            // Final Score = Base + Risk Modifier + Performance Modifier
            $finalPriority = $priority + $aiRiskModifier + $aiPerformanceModifier;

            // Generate Explanation
            $reason = $this->generateReasonString($urgencyReason, $riskLevel, $predictedScore);

            // Calculate total revision hours needed
            $totalHoursNeeded = ($riskLevel == 'high') ? 8 : (($riskLevel == 'medium') ? 6 : 4);
            
            // ADAPTIVE DEDUCTION: Calculate completed hours only from past completed plans (not cancelled drafts)
            $completedMinutes = StudyPlanItem::whereHas('studyPlan', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })->where('subject_id', $exam->subject_id)
              ->whereNull('assignment_id')
              ->where('status', 'completed')
              ->sum('duration_minutes');
              
            $remainingHours = max(0, $totalHoursNeeded - ($completedMinutes / 60));
            
            // Skip if already completed sufficient revision
            if ($remainingHours <= 0) {
                Log::info("Study Plan Gen - Skipping {$exam->title} revision (Already completed {$totalHoursNeeded}h)");
                continue;
            }
            
            // Distribute Revision Across Available Days
            $availableDaysBeforeExam = $this->getAvailableDaysBetween($availabilities, $today, $examDate);
            
            if (count($availableDaysBeforeExam) > 0) {
                // Distribute evenly but cap at remainingHours
                $hoursPerDay = max(1, ceil($remainingHours / count($availableDaysBeforeExam)));
                $allocatedHours = 0;
                
                foreach ($availableDaysBeforeExam as $targetDate) {
                    $hoursForThisDay = min($hoursPerDay, $remainingHours - $allocatedHours);
                    if ($hoursForThisDay <= 0) break;
                    
                    $tasks[] = [
                        'type' => 'exam',
                        'subject_id' => $exam->subject_id,
                        'title' => ($exam->subject?->name ?? 'Uncategorized') . ' Revision [' . $reason . ']',
                        'target_date' => Carbon::parse($targetDate), // Preferred study day
                        'deadline' => $examDate,
                        'hours_needed' => $hoursForThisDay,
                        'priority' => $finalPriority
                    ];
                    
                    $allocatedHours += $hoursForThisDay;
                }
            } else {
                $tasks[] = [
                    'type' => 'exam',
                    'subject_id' => $exam->subject_id,
                    'title' => ($exam->subject?->name ?? 'Uncategorized') . ' Revision [' . $reason . ']',
                    'target_date' => null,
                    'deadline' => $examDate,
                    'hours_needed' => $remainingHours,
                    'priority' => $finalPriority
                ];
            }
        }

        // 3. Build the Assignment Queue
        foreach ($assignments as $assignment) {
            $deadlineDate = Carbon::parse($assignment->deadline)->startOfDay();
            $daysUntilDeadline = $today->diffInDays($deadlineDate, false);
            
            // Skip past deadlines
            if ($daysUntilDeadline < 0) continue;

            $priority = 30;
            $urgencyReason = 'Pending Assignment';

            if ($assignment->priority == 'high') { $priority += 10; $urgencyReason = 'High Priority Assignment'; }

            if ($daysUntilDeadline <= 2) { $priority += 50; $urgencyReason = 'Due within 2 days'; }
            elseif ($daysUntilDeadline <= 3) { $priority += 40; $urgencyReason = 'Due within 3 days'; }
            elseif ($daysUntilDeadline <= 7) { $priority += 20; $urgencyReason = 'Due within 7 days'; }

            $finalPriority = $priority + $aiRiskModifier + $aiPerformanceModifier;
            $reason = $this->generateReasonString($urgencyReason, $riskLevel, $predictedScore);

            $totalHoursNeeded = $assignment->estimated_hours ?: 2;
            
            // ADAPTIVE DEDUCTION: Calculate completed hours only from past completed plans
            $completedMinutes = StudyPlanItem::whereHas('studyPlan', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })->where('assignment_id', $assignment->id)
              ->where('status', 'completed')
              ->sum('duration_minutes');
              
            $remainingHours = max(0, $totalHoursNeeded - ($completedMinutes / 60));
            
            // Skip if assignment is already adaptively completed via sessions
            if ($remainingHours <= 0) {
                Log::info("Study Plan Gen - Skipping {$assignment->title} (Already completed {$totalHoursNeeded}h)");
                continue;
            }

            // For assignments, we let them float to the highest priority available day before deadline
            $tasks[] = [
                'type' => 'assignment',
                'subject_id' => $assignment->subject_id,
                'assignment_id' => $assignment->id,
                'title' => $assignment->title . ' [' . $reason . ']',
                'target_date' => null, // Can be scheduled on any day before deadline
                'deadline' => $deadlineDate,
                'hours_needed' => $remainingHours,
                'priority' => $finalPriority
            ];
        }

        // Sort tasks by priority descending (highest first)
        usort($tasks, function($a, $b) {
            return $b['priority'] <=> $a['priority'];
        });
        
        Log::info("Study Plan Gen - Tasks created in queue: " . count($tasks));
        $sessionsCreated = 0;

        // 4. Fill the schedule for the next 14 days
        $currentDate = now()->startOfDay();
        $endDate = now()->addDays(14)->startOfDay();

        while ($currentDate <= $endDate) {
            $dayOfWeek = $currentDate->dayOfWeek; // 0 (Sunday) to 6 (Saturday)
            
            if ($availabilities->has($dayOfWeek)) {
                $avail = $availabilities->get($dayOfWeek);
                $startTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $avail->start_time);
                $endTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $avail->end_time);
                
                // If the availability block is in the past (e.g. today but earlier hours), skip
                $skipDay = false;
                if ($startTime < now()) {
                    $startTime = now()->addMinutes(15); // Start soon if available today
                    // If the adjusted start time is past the end time, day is gone
                    if ($startTime >= $endTime) {
                        $skipDay = true;
                    }
                }

                if (!$skipDay) {
                    $currentTime = $startTime->copy();

                    foreach ($tasks as &$task) {
                        if ($task['hours_needed'] <= 0) continue;
                        
                        // Don't schedule after the deadline
                        if ($currentDate > $task['deadline']) continue;

                        // If this task has a preferred target date and today is not that day,
                        // check if the target date is in the future. If so, wait for that date.
                        if ($task['target_date'] !== null && !$currentDate->isSameDay($task['target_date'])) {
                            if ($currentDate < $task['target_date']) {
                                continue;
                            }
                        }

                        // Calculate available remaining time in this availability window
                        $availableMinutes = $currentTime->diffInMinutes($endTime);
                        if ($availableMinutes < 30) break; // Don't fit tiny micro-slots

                        // Maximum session duration 120 mins (2h), minimum 30 mins
                        $sessionHours = min($task['hours_needed'], min(2, $availableMinutes / 60));
                        $sessionMinutes = (int) round($sessionHours * 60);
                        
                        if ($sessionMinutes < 30) continue;

                        $proposedEndTime = $currentTime->copy()->addMinutes($sessionMinutes);
                        
                        // Check if we have enough time left in the day block
                        if ($proposedEndTime <= $endTime) {
                            // Create item safely
                            if (!$this->checkConflict($user->id, $currentDate->format('Y-m-d'), $currentTime->format('H:i:s'), $proposedEndTime->format('H:i:s'))) {
                                $plan->studyPlanItems()->create([
                                    'subject_id' => $task['subject_id'],
                                    'assignment_id' => $task['type'] == 'assignment' ? $task['assignment_id'] : null,
                                    'title' => $task['title'],
                                    'study_date' => $currentDate->format('Y-m-d'),
                                    'start_time' => $currentTime->format('H:i:s'),
                                    'end_time' => $proposedEndTime->format('H:i:s'),
                                    'duration_minutes' => $sessionMinutes,
                                    'status' => 'pending'
                                ]);

                                $task['hours_needed'] -= $sessionHours;
                                $currentTime = $proposedEndTime->copy(); // Move pointer forward
                                
                                // Add a mandatory 15-minute break between sessions
                                $currentTime->addMinutes(15);
                                $sessionsCreated++;
                            } else {
                                // If there was a conflict, skip ahead 30 mins and try again
                                $currentTime->addMinutes(30);
                            }
                        }
                        
                        if ($currentTime >= $endTime) break; // Day is full
                    }
                }
            }
            $currentDate->addDay();
        }

        Log::info("Study Plan Gen - Study sessions created: " . $sessionsCreated);
        return $plan;
    }

    /**
     * Calculate priority modifier based on AI Academic Risk.
     */
    private function calculateRiskModifier(string $riskLevel): int
    {
        return match($riskLevel) {
            'high' => 30,
            'medium' => 15,
            'low' => 0,
            default => 0,
        };
    }

    /**
     * Calculate priority modifier based on AI Predicted Exam Score.
     */
    private function calculatePerformanceModifier(?float $score): int
    {
        if ($score === null) return 0;
        
        if ($score < 50) return 30;
        if ($score <= 65) return 20;
        if ($score <= 75) return 10;
        return 0; // Above 75 gets standard priority
    }

    /**
     * Construct a transparent explanation for why this task was scheduled.
     */
    private function generateReasonString(string $urgency, string $risk, ?float $score): string
    {
        $reason = $urgency;
        if ($risk == 'high' || ($score !== null && $score < 65)) {
            $reason .= ' - Attention Required';
        }
        return $reason;
    }

    /**
     * Find all actual available study dates between now and a target date.
     */
    private function getAvailableDaysBetween($availabilities, Carbon $startDate, Carbon $endDate): array
    {
        $days = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            if ($availabilities->has($current->dayOfWeek)) {
                $days[] = $current->format('Y-m-d');
            }
            $current->addDay();
        }
        
        return $days;
    }

    /**
     * Check if a proposed time conflicts with existing sessions.
     */
    public function checkConflict($userId, $date, $startTime, $endTime, $excludeItemId = null): bool
    {
        $query = StudyPlanItem::whereHas('studyPlan', function($q) use ($userId) {
            $q->where('user_id', $userId)->where('status', 'active');
        })
        ->whereDate('study_date', $date)
        ->where(function($q) use ($startTime, $endTime) {
            $q->where(function($q2) use ($startTime, $endTime) {
                // Starts during an existing session
                $q2->where('start_time', '<=', $startTime)
                   ->where('end_time', '>', $startTime);
            })->orWhere(function($q2) use ($startTime, $endTime) {
                // Ends during an existing session
                $q2->where('start_time', '<', $endTime)
                   ->where('end_time', '>=', $endTime);
            })->orWhere(function($q2) use ($startTime, $endTime) {
                // Encompasses an existing session
                $q2->where('start_time', '>=', $startTime)
                   ->where('end_time', '<=', $endTime);
            });
        });

        if ($excludeItemId) {
            $query->where('id', '!=', $excludeItemId);
        }

        return $query->exists();
    }
}
