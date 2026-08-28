<?php
namespace App\Http\Controllers;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index() { return view('exams.index', ['exams' => Auth::user()->exams()->with('subject')->get()]); }
    public function create() { return view('exams.create', ['subjects' => Auth::user()->subjects]); }
    public function store(Request $request) {
        $request->validate(['title' => 'required', 'subject_id' => 'required|exists:subjects,id', 'exam_date' => 'required|date', 'exam_type' => 'required', 'status' => 'required|in:upcoming,completed', 'score' => 'nullable|numeric|min:0|max:100']);
        Auth::user()->exams()->create($request->all());
        return redirect()->route('exams.index')->with('status', 'Exam added.');
    }
    public function edit(Exam $exam) {
        if ($exam->user_id !== Auth::id()) abort(403);
        return view('exams.edit', ['exam' => $exam, 'subjects' => Auth::user()->subjects]);
    }
    public function update(Request $request, Exam $exam) {
        if ($exam->user_id !== Auth::id()) abort(403);
        $request->validate(['title' => 'required', 'subject_id' => 'required|exists:subjects,id', 'exam_date' => 'required|date', 'exam_type' => 'required', 'status' => 'required|in:upcoming,completed', 'score' => 'nullable|numeric|min:0|max:100']);
        $exam->update($request->all());
        return redirect()->route('exams.index')->with('status', 'Exam updated.');
    }
    public function destroy(Exam $exam) {
        if ($exam->user_id !== Auth::id()) abort(403);
        $exam->delete();
        return redirect()->route('exams.index')->with('status', 'Exam deleted.');
    }
}