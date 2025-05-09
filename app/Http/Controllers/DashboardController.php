<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::with('division')->get();
        
        // Hitung fuzzy untuk setiap project
        foreach ($projects as $project) {
            $processingTime = $this->calculateFuzzyProcessingTime($project);
            $project->calculated_processing_time = $processingTime;
        }
    
        return view('dashboard', compact('projects'));
    }

    private function calculateFuzzyProcessingTime($project)
    {
        // Mapping output ke nilai z crisp
        $zMapping = [
            'selesai_cepat'   => 40,
            'sesuai_deadline' => 50,
            'telat'           => 60,
        ];
    
        // Hitung membership functions
        $employeeMembership = [
            'sedikit' => $this->calculateMembership($project->employee_count, 1, 2, 3),
            'sedang'  => $this->calculateMembership($project->employee_count, 2, 3, 4),
            'banyak'  => $this->calculateMembership($project->employee_count, 3, 4, 5),
        ];
    
        $workingHoursMembership = [
            'rendah' => $this->calculateMembership($project->working_hours, 5, 15, 25),
            'sedang' => $this->calculateMembership($project->working_hours, 20, 30, 40),
            'tinggi' => $this->calculateMembership($project->working_hours, 35, 45, 56)
        ];
    
        $priorityMembership = [
            'bisa_didahulukan' => $this->calculateMembership($project->priority_scale, 0, 1, 2),
            'normal' => $this->calculateMembership($project->priority_scale, 1, 2, 3),
            'penting' => $this->calculateMembership($project->priority_scale, 2, 3, 4),
            'super_penting' => $this->calculateMembership($project->priority_scale, 3, 4, 4)
        ];
    
        // Proses fuzzy rules
        $zSum = 0;
        $alphaSum = 0;
    
        $rules = [
            ['sedikit', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
            ['banyak', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
            ['sedang', 'sedang', 'normal', 'sesuai_deadline'],
            ['sedikit', 'tinggi', 'penting', 'telat'],
            ['sedikit', 'tinggi', 'super_penting', 'telat'],
            ['banyak', 'tinggi', 'super_penting', 'telat'],
            ['banyak', 'rendah', 'super_penting', 'telat']
        ];
    
        foreach ($rules as $rule) {
            [$e, $w, $p, $outLabel] = $rule;
            
            $alpha = min(
                $employeeMembership[$e] ?? 0,
                $workingHoursMembership[$w] ?? 0,
                $priorityMembership[$p] ?? 0
            );
    
            if ($alpha > 0) {
                $z = $zMapping[$outLabel];
                $zSum += $alpha * $z;
                $alphaSum += $alpha;
            }
        }
    
        return $alphaSum > 0 ? round($zSum / $alphaSum) : 40; // Default to 40 if no rules match
    }

    private function calculateMembership($x, $a, $b, $c)
    {
        if ($x < $a || $x > $c) return 0;
        if ($x == $b) return 1;
        if ($x < $b) return ($x - $a) / ($b - $a);
        return ($c - $x) / ($c - $b);
    }
}