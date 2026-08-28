<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\SmartAlertService;

class DashboardController extends Controller
{
    protected $smartAlertService;

    public function __construct(SmartAlertService $smartAlertService)
    {
        $this->smartAlertService = $smartAlertService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Synchronize Smart Alerts before loading dashboard
        $this->smartAlertService->syncUserAlerts($user);

        $profile = $user->studentProfile;
        $assignments = $user->assignments()->where('status', '!=', 'completed')->orderBy('deadline', 'asc')->take(5)->get();
        $exams = $user->exams()->orderBy('exam_date', 'asc')->take(5)->get();
        $predictions = $user->predictions()->latest()->first();
        $recentRecommendations = $user->recommendations()->where('is_completed', false)->latest()->take(3)->get();
        
        $activePlan = $user->studyPlans()->where('status', 'active')->first();
        $todaySessions = [];
        $planProgress = 0;
        
        if ($activePlan) {
            $todaySessions = $activePlan->studyPlanItems()->whereDate('study_date', now())->orderBy('start_time')->get();
            $totalItems = $activePlan->studyPlanItems()->count();
            $completedItems = $activePlan->studyPlanItems()->where('status', 'completed')->count();
            if ($totalItems > 0) {
                $planProgress = round(($completedItems / $totalItems) * 100);
            }
        }
        
        $smartAlerts = $user->smartAlerts()->where('is_read', false)->latest()->get();
        
        return view('dashboard', compact('profile', 'assignments', 'exams', 'predictions', 'recentRecommendations', 'todaySessions', 'activePlan', 'planProgress', 'smartAlerts'));
    }
}