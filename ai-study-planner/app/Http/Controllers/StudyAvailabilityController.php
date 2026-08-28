<?php
namespace App\Http\Controllers;
use App\Models\StudyAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyAvailabilityController extends Controller
{
    public function index() { return view('availability.index', ['availabilities' => Auth::user()->studyAvailabilities]); }
    public function create() { return view('availability.create'); }
    public function store(Request $request) {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6', 
            'start_time' => 'required', 
            'end_time' => 'required|after:start_time'
        ]);
        
        $start = \Carbon\Carbon::parse($validated['start_time']);
        $end = \Carbon\Carbon::parse($validated['end_time']);
        $validated['available_hours'] = $start->diffInMinutes($end) / 60;

        Auth::user()->studyAvailabilities()->create($validated);
        return redirect()->route('availability.index')->with('status', 'Availability added.');
    }
    public function edit(StudyAvailability $availability) {
        if ($availability->user_id !== Auth::id()) abort(403);
        return view('availability.edit', compact('availability'));
    }
    public function update(Request $request, StudyAvailability $availability) {
        if ($availability->user_id !== Auth::id()) abort(403);
        $validated = $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6', 
            'start_time' => 'required', 
            'end_time' => 'required|after:start_time'
        ]);
        
        $start = \Carbon\Carbon::parse($validated['start_time']);
        $end = \Carbon\Carbon::parse($validated['end_time']);
        $validated['available_hours'] = $start->diffInMinutes($end) / 60;

        $availability->update($validated);
        return redirect()->route('availability.index')->with('status', 'Availability updated.');
    }
    public function destroy(StudyAvailability $availability) {
        if ($availability->user_id !== Auth::id()) abort(403);
        $availability->delete();
        return redirect()->route('availability.index')->with('status', 'Availability deleted.');
    }
}