<?php

namespace App\Services;

class FuzzyTsukamotoService
{
    public function calculateOvertimeProbability($processingTime, $priorityLevel, $difficultyLevel)
    {
        // Fuzzification
        $processingTimeMembership = $this->fuzzifyProcessingTime($processingTime);
        $priorityMembership = $this->fuzzifyPriority($priorityLevel);
        $difficultyMembership = $this->fuzzifyDifficulty($difficultyLevel);

        // Rule Evaluation
        $rules = $this->evaluateRules($processingTimeMembership, $priorityMembership, $difficultyMembership);

        // Defuzzification
        return $this->defuzzify($rules);
    }

    private function fuzzifyProcessingTime($time)
    {
        // Processing Time membership functions (Short, Medium, Long)
        $short = max(0, min(1, (10 - $time) / 5));
        $medium = max(0, min(($time - 5) / 5, (15 - $time) / 5));
        $long = max(0, min(1, ($time - 10) / 5));

        return [
            'short' => $short,
            'medium' => $medium,
            'long' => $long
        ];
    }

    private function fuzzifyPriority($priority)
    {
        // Priority membership functions (Low, Medium, High)
        $low = max(0, min(1, (3 - $priority) / 2));
        $medium = max(0, min(($priority - 1) / 2, (5 - $priority) / 2));
        $high = max(0, min(1, ($priority - 3) / 2));

        return [
            'low' => $low,
            'medium' => $medium,
            'high' => $high
        ];
    }

    private function fuzzifyDifficulty($difficulty)
    {
        // Difficulty membership functions (Easy, Medium, Hard)
        $easy = max(0, min(1, (3 - $difficulty) / 2));
        $medium = max(0, min(($difficulty - 1) / 2, (5 - $difficulty) / 2));
        $hard = max(0, min(1, ($difficulty - 3) / 2));

        return [
            'easy' => $easy,
            'medium' => $medium,
            'hard' => $hard
        ];
    }

    private function evaluateRules($processingTime, $priority, $difficulty)
    {
        $rules = [];

        // Rule base
        // Example: IF processing_time is long AND priority is high AND difficulty is hard THEN overtime_needed is high
        $rules[] = [
            'weight' => min($processingTime['long'], $priority['high'], $difficulty['hard']),
            'output' => 90 // High overtime probability
        ];

        // Add more rules here based on your business logic
        $rules[] = [
            'weight' => min($processingTime['medium'], $priority['medium'], $difficulty['medium']),
            'output' => 50 // Medium overtime probability
        ];

        $rules[] = [
            'weight' => min($processingTime['short'], $priority['low'], $difficulty['easy']),
            'output' => 10 // Low overtime probability
        ];

        return $rules;
    }

    private function defuzzify($rules)
    {
        $weightedSum = 0;
        $weightsSum = 0;

        foreach ($rules as $rule) {
            $weightedSum += $rule['weight'] * $rule['output'];
            $weightsSum += $rule['weight'];
        }

        return $weightsSum > 0 ? $weightedSum / $weightsSum : 0;
    }
}