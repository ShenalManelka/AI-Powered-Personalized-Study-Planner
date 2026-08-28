<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StudyAvailabilityController;
use App\Http\Controllers\SmartAlertController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\StudyPlanController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\AIAssistantController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/academic', [ProfileController::class, 'updateAcademic'])->name('profile.academic.update');
    
    // Student Profile extended
    Route::get('/student-profile/edit', [StudentProfileController::class, 'edit'])->name('student-profile.edit');
    Route::put('/student-profile', [StudentProfileController::class, 'update'])->name('student-profile.update');
    
    // CRUD Resources
    Route::resource('subjects', SubjectController::class)->except(['show']);
    Route::resource('assignments', AssignmentController::class)->except(['show']);
    Route::resource('exams', ExamController::class)->except(['show']);
    Route::resource('availability', StudyAvailabilityController::class)->except(['show']);
    
    // AI Predictions
    Route::get('/predictions', [PredictionController::class, 'index'])->name('predictions.index');
    Route::post('/predictions/analyze', [PredictionController::class, 'analyze'])->name('predictions.analyze');
    
    // Recommendations
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
    Route::post('/recommendations/generate', [RecommendationController::class, 'generate'])->name('recommendations.generate');
    Route::patch('/recommendations/{recommendation}/complete', [RecommendationController::class, 'markCompleted'])->name('recommendations.complete');

    // Smart Alerts
    Route::post('/alerts/{alert}/read', [SmartAlertController::class, 'markAsRead'])->name('alerts.read');

    // Study Plans
    Route::get('/study-plans', [StudyPlanController::class, 'index'])->name('study-plans.index');
    Route::post('/study-plans/generate', [StudyPlanController::class, 'generate'])->name('study-plans.generate');
    Route::post('/study-plans/sessions', [StudyPlanController::class, 'addManualSession'])->name('study-plans.sessions.store');
    Route::put('/study-plans/sessions/{id}', [StudyPlanController::class, 'updateSession'])->name('study-plans.sessions.update');
    Route::delete('/study-plans/sessions/{id}', [StudyPlanController::class, 'deleteSession'])->name('study-plans.sessions.destroy');
    Route::patch('/study-plans/sessions/{id}/complete', [StudyPlanController::class, 'completeSession'])->name('study-plans.sessions.complete');
    // AI Assistant API routes (handled entirely via JS in the frontend)
    Route::post('/api/assistant/ask', [AIAssistantController::class, 'ask'])->name('assistant.ask');
    Route::post('/api/assistant/explain', [AIAssistantController::class, 'explain'])->name('assistant.explain');
    Route::post('/api/assistant/quiz', [AIAssistantController::class, 'quiz'])->name('assistant.quiz');
});

require __DIR__.'/auth.php';
