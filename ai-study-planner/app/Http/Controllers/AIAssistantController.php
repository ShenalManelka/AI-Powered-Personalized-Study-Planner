<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class AIAssistantController extends Controller
{
    protected GeminiService $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Handle "Ask AI" requests.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        $response = $this->geminiService->askQuestion($request->question);

        if ($response) {
            return response()->json(['success' => true, 'answer' => $response]);
        }

        return response()->json([
            'success' => false, 
            'error' => 'Unable to get a response from the AI Assistant. Please try again later.'
        ], 500);
    }

    /**
     * Handle "Explain Topic" requests.
     */
    public function explain(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
        ]);

        $response = $this->geminiService->explainTopic($request->topic);

        if ($response) {
            return response()->json(['success' => true, 'explanation' => $response]);
        }

        return response()->json([
            'success' => false, 
            'error' => 'Unable to generate an explanation at this time. Please try again later.'
        ], 500);
    }

    /**
     * Handle "Generate Quiz" requests.
     */
    public function quiz(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'difficulty' => 'required|in:Easy,Medium,Hard',
            'numQuestions' => 'required|integer|in:5,10',
        ]);

        $quiz = $this->geminiService->generateQuiz(
            $request->subject,
            $request->topic,
            $request->difficulty,
            $request->numQuestions
        );

        if ($quiz) {
            return response()->json(['success' => true, 'quiz' => $quiz]);
        }

        return response()->json([
            'success' => false, 
            'error' => 'Unable to generate the quiz. The AI service may be temporarily unavailable.'
        ], 500);
    }
}
