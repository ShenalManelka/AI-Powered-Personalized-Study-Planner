<?php
namespace App\Http\Controllers;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProfileController extends Controller
{
    public function edit()
    {
        $profile = Auth::user()->studentProfile ?? new StudentProfile();
        return view('profile.student.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'study_hours' => 'numeric|min:0',
            'attendance' => 'numeric|min:0|max:100',
            'sleep_hours' => 'numeric|min:0|max:24',
            'internet_usage' => 'numeric|min:0',
            'assignments_completed' => 'integer|min:0',
            'previous_score' => 'nullable|numeric|min:0|max:100',
        ]);
        
        $profile = Auth::user()->studentProfile;
        if (!$profile) {
            Auth::user()->studentProfile()->create($request->all());
        } else {
            $profile->update($request->all());
        }
        return redirect()->route('dashboard')->with('status', 'Profile updated successfully.');
    }
}