<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.flask.url');
    }

    /**
     * Send a POST request to the Flask API.
     */
    protected function postRequest(string $endpoint, array $data)
    {
        try {
            $response = Http::timeout(5)->post($this->baseUrl . $endpoint, $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Flask API Error on {$endpoint}: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Flask API Connection Failed on {$endpoint}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Predict student performance.
     */
    public function predictPerformance(array $studentData): ?float
    {
        $result = $this->postRequest('/predict/performance', $studentData);
        return $result['predicted_exam_score'] ?? null;
    }

    /**
     * Predict academic risk.
     */
    public function predictRisk(array $studentData): ?string
    {
        $result = $this->postRequest('/predict/risk', $studentData);
        return $result['predicted_risk'] ?? null;
    }

    /**
     * Predict student cluster.
     */
    public function predictCluster(array $studentData): ?array
    {
        $result = $this->postRequest('/predict/cluster', $studentData);
        return $result ? [
            'cluster' => $result['cluster'] ?? null,
            'cluster_label' => $result['cluster_label'] ?? null,
        ] : null;
    }

    /**
     * Get personalized recommendations.
     */
    public function getRecommendations(array $data): ?array
    {
        $result = $this->postRequest('/recommendations', $data);
        return $result;
    }
}
