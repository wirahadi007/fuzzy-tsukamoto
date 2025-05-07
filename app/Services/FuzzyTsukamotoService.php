<?php

namespace App\Services;

class FuzzyTsukamotoService
{
    private const OUTPUT_MIN = 40;
    private const OUTPUT_MAX = 60;

    public function calculateOvertimeProbability($employeeCount, $processingTime, $priorityLevel)
    {
        // Fuzzification
        $employeeMembership = $this->fuzzifyEmployeeCount($employeeCount);
        $timeMembership = $this->fuzzifyProcessingTime($processingTime);
        $priorityMembership = $this->fuzzifyPriority($priorityLevel);

        // Rule Evaluation & Defuzzification
        $alpha = $this->evaluateRule($employeeMembership, $timeMembership, $priorityMembership);
        
        // Using the formula from the image: z = a + (b-a) * α
        // where a = 40, b = 60
        $z = self::OUTPUT_MIN + (self::OUTPUT_MAX - self::OUTPUT_MIN) * $alpha;
        
        return $z;
    }

    private function evaluateRule($employee, $time, $priority)
    {
        // Calculate α-predikat using MIN operator
        return min(
            $employee['value'],
            $time['value'],
            $priority['value']
        );
    }

    private function fuzzifyEmployeeCount($count)
    {
        // Implement based on your membership functions
        $value = $this->calculateMembershipValue($count, 1, 5);
        return ['value' => $value];
    }

    private function fuzzifyProcessingTime($time)
    {
        // Implement based on your membership functions
        $value = $this->calculateMembershipValue($time, 5, 56);
        return ['value' => $value];
    }

    private function fuzzifyPriority($priority)
    {
        // Implement based on your membership functions
        $value = $this->calculateMembershipValue($priority, 1, 4);
        return ['value' => $value];
    }

    private function calculateMembershipValue($x, $min, $max)
    {
        if ($x <= $min) return 0;
        if ($x >= $max) return 1;
        return ($x - $min) / ($max - $min);
    }
}