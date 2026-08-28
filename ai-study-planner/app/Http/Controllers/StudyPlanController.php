<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\StudyPlanService;
use App\Models\StudyPlan;
use App\Models\StudyPlanItem;
use Carbon\Carbon;

class StudyPlanController extends Controller
{
    protected StudyPlanService $studyPlanService;

    public function __construct(StudyPlanService $studyPlanService)
    {
        $this->studyPlanService = $studyPlanService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $plan = $user->studyPlans()->where('status', 'active')->first();
        
        $items = collect();
        $groupedItems = collect();
        $totalSessions = 0;
        $completedSessions = 0;
        $progress = 0;
        
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        $anchorDate = Carbon::parse($selectedDate);
        $startOfWeek = $anchorDate->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $anchorDate->copy()->endOfWeek(Carbon::SUNDAY);
        
        $autoGenerateError = null;
        $noTasksMessage = null;

        // Auto-generate if no active plan
        if (!$plan && !$request->has('date')) {
            try {
                $plan = $this->studyPlanService->generatePlan($user);
            } catch (\Exception $e) {
                $autoGenerateError = $e->getMessage();
            }
        }

        if ($plan) {
            $items = $plan->studyPlanItems()
                ->whereBetween('study_date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                ->orderBy('study_date')
                ->orderBy('start_time')
                ->get();
                
            $groupedItems = $items->groupBy(function($item) {
                return Carbon::parse($item->study_date)->format('Y-m-d');
            });
                
            $totalSessions = $plan->studyPlanItems()->count();
            $completedSessions = $plan->studyPlanItems()->where('status', 'completed')->count();
            if ($totalSessions > 0) {
                $progress = round(($completedSessions / $totalSessions) * 100);
            } else {
                $noTasksMessage = "No upcoming academic tasks are available for scheduling.";
            }
        }

        $subjects = $user->subjects;
        $weekDays = [];
        for ($i = 0; $i < 7; $i++) {
            $weekDays[] = $startOfWeek->copy()->addDays($i);
        }

        return view('study_plans.index', compact(
            'plan', 'groupedItems', 'selectedDate', 'startOfWeek', 'endOfWeek', 'weekDays',
            'totalSessions', 'completedSessions', 'progress', 'subjects', 'autoGenerateError', 'noTasksMessage'
        ));
    }

    public function generate(Request $request)
    {
        try {
            $this->studyPlanService->generatePlan(Auth::user());
            return redirect()->route('study-plans.index')->with('status', 'Your personalized study plan has been regenerated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('study-plans.index')->with('error', $e->getMessage());
        }
    }

    public function addManualSession(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'study_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $user = Auth::user();
        $plan = $user->studyPlans()->where('status', 'active')->first();

        if (!$plan) {
            return back()->with('error', 'You need an active study plan first. Generate one before adding manual sessions.');
        }

        // Verify Subject Ownership
        $subject = $user->subjects()->findOrFail($request->subject_id);

        // Check conflicts
        if ($this->studyPlanService->checkConflict($user->id, $request->study_date, $request->start_time, $request->end_time)) {
            return back()->with('error', 'Study session conflicts with an existing session.');
        }

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $durationMinutes = $end->diffInMinutes($start);

        $plan->studyPlanItems()->create([
            'subject_id' => $subject->id,
            'title' => $request->title,
            'study_date' => $request->study_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $durationMinutes,
            'status' => 'pending'
        ]);

        return back()->with('status', 'Manual session added successfully.');
    }

    public function updateSession(Request $request, $id)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'study_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s,H:i',
            'end_time' => 'required|date_format:H:i:s,H:i|after:start_time',
        ]);

        $user = Auth::user();
        $item = StudyPlanItem::whereHas('studyPlan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        $subject = $user->subjects()->findOrFail($request->subject_id);

        if ($this->studyPlanService->checkConflict($user->id, $request->study_date, $request->start_time, $request->end_time, $item->id)) {
            return back()->with('error', 'Study session conflicts with an existing session.');
        }

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $durationMinutes = $end->diffInMinutes($start);

        $item->update([
            'subject_id' => $subject->id,
            'title' => $request->title,
            'study_date' => $request->study_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $durationMinutes,
        ]);

        return back()->with('status', 'Session updated successfully.');
    }

    public function deleteSession($id)
    {
        $user = Auth::user();
        $item = StudyPlanItem::whereHas('studyPlan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        $item->delete();

        return back()->with('status', 'Session deleted successfully.');
    }

    public function completeSession($id)
    {
        $user = Auth::user();
        $item = StudyPlanItem::whereHas('studyPlan', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        $item->update([
            'status' => $item->status == 'completed' ? 'pending' : 'completed'
        ]);

        return back()->with('status', 'Session status updated.');
    }
}
