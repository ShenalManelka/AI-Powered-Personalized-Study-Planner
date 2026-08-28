<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recommendation;

class RecommendationController extends Controller
{
    public function index()
    {
        $recommendations = Auth::user()->recommendations()->orderBy('is_completed')->latest()->get();
        return view('recommendations.index', compact('recommendations'));
    }

    public function markCompleted(Request $request, Recommendation $recommendation)
    {
        if ($recommendation->user_id !== Auth::id()) {
            abort(403);
        }

        $recommendation->update([
            'is_completed' => !$recommendation->is_completed
        ]);

        return redirect()->route('recommendations.index')->with('status', 'Recommendation status updated.');
    }
}
