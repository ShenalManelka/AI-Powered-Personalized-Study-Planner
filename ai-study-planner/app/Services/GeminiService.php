<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? '';
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';
    }

    /**
     * Ask a general educational question.
     */
    public function askQuestion(string $question): ?string
    {
        $prompt = "You are a helpful and educational AI study assistant. Answer the following question simply and clearly:\n\n" . $question;
        return $this->generateContent($prompt);
    }

    /**
     * Explain a specific topic.
     */
    public function explainTopic(string $topic): ?string
    {
        $prompt = "You are an expert tutor. Please explain the topic '{$topic}' in simple, easy-to-understand terms. Use analogies if helpful. Keep it concise but highly educational.";
        return $this->generateContent($prompt);
    }

    /**
     * Generate a multiple-choice quiz.
     */
    public function generateQuiz(string $subject, string $topic, string $difficulty, int $numQuestions): ?array
    {
        $prompt = "Generate a multiple-choice quiz about '{$topic}' in the subject of '{$subject}'.\n"
            . "Difficulty: {$difficulty}.\n"
            . "Number of questions: {$numQuestions}.\n\n"
            . "You must respond with ONLY a valid JSON array. Each element in the array must be an object with the following exact keys:\n"
            . "- 'question': The question text.\n"
            . "- 'options': An array of exactly 4 string options.\n"
            . "- 'correct_answer': The exact string of the correct option (must exactly match one of the options).\n"
            . "- 'explanation': A brief explanation of why the answer is correct.\n\n"
            . "Do not include markdown blocks like ```json or any other text outside the array.";

        $response = $this->generateContent($prompt, true);

        if (!$response) {
            return null;
        }

        // Clean potential markdown blocks just in case
        $response = preg_replace('/```json|```/', '', $response);
        $response = trim($response);

        $data = json_decode($response, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            // Normalize keys (Gemini sometimes returns 'answer' instead of 'correct_answer')
            foreach ($data as &$question) {
                if (isset($question['answer']) && !isset($question['correct_answer'])) {
                    $question['correct_answer'] = $question['answer'];
                }
            }
            return $data;
        }

        return null;
    }

    /**
     * Helper to make the API call to Gemini.
     */
    protected function generateContent(string $prompt, bool $jsonMode = false): ?string
    {
        if (empty($this->apiKey)) {
            Log::error('Gemini API Key is not set.');
            return null;
        }

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ];

        if ($jsonMode) {
            $payload['generationConfig'] = [
                'responseMimeType' => 'application/json'
            ];
        }

        try {
            $response = Http::withoutVerifying()->timeout(30)->post($this->baseUrl . '?key=' . $this->apiKey, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            }

            Log::error("Gemini API Error: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Gemini API Connection Failed: " . $e->getMessage());
            return null;
        }
    }
}
