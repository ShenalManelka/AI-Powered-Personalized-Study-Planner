<?php
namespace App\Http\Controllers;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index() { return view('subjects.index', ['subjects' => Auth::user()->subjects]); }
    public function create() { return view('subjects.create'); }
    public function store(Request $request) {
        $request->validate(['name' => 'required', 'code' => 'required']);
        Auth::user()->subjects()->create($request->all());
        return redirect()->route('subjects.index')->with('status', 'Subject added.');
    }
    public function edit(Subject $subject) {
        if ($subject->user_id !== Auth::id()) abort(403);
        return view('subjects.edit', compact('subject'));
    }
    public function update(Request $request, Subject $subject) {
        if ($subject->user_id !== Auth::id()) abort(403);
        $request->validate(['name' => 'required', 'code' => 'required']);
        $subject->update($request->all());
        return redirect()->route('subjects.index')->with('status', 'Subject updated.');
    }
    public function destroy(Subject $subject) {
        if ($subject->user_id !== Auth::id()) abort(403);
        $subject->delete();
        return redirect()->route('subjects.index')->with('status', 'Subject deleted.');
    }
}