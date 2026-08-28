<?php
namespace App\Http\Controllers;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index() { return view('assignments.index', ['assignments' => Auth::user()->assignments()->with('subject')->get()]); }
    public function create() { return view('assignments.create', ['subjects' => Auth::user()->subjects]); }
    public function store(Request $request) {
        $request->validate(['title' => 'required', 'subject_id' => 'required|exists:subjects,id', 'deadline' => 'required|date']);
        Auth::user()->assignments()->create($request->all());
        return redirect()->route('assignments.index')->with('status', 'Assignment added.');
    }
    public function edit(Assignment $assignment) {
        if ($assignment->user_id !== Auth::id()) abort(403);
        return view('assignments.edit', ['assignment' => $assignment, 'subjects' => Auth::user()->subjects]);
    }
    public function update(Request $request, Assignment $assignment) {
        if ($assignment->user_id !== Auth::id()) abort(403);
        $request->validate(['title' => 'required', 'subject_id' => 'required|exists:subjects,id', 'deadline' => 'required|date']);
        $assignment->update($request->all());
        return redirect()->route('assignments.index')->with('status', 'Assignment updated.');
    }
    public function destroy(Assignment $assignment) {
        if ($assignment->user_id !== Auth::id()) abort(403);
        $assignment->delete();
        return redirect()->route('assignments.index')->with('status', 'Assignment deleted.');
    }
}