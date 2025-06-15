<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('division')->get();
        return view('projects.index', compact('projects'));
    }

    public function fuzzyAnalysis()
    {
        try {
            $projects = Project::with('division')->get();
    
            if ($projects->isEmpty()) {
                return view('projects.fuzzy-analysis')->with('message', 'No projects found');
            }
    
            // Mapping output ke nilai z crisp
            $zMapping = [
                'selesai_cepat'     => 40,
                'sesuai_deadline'   => 50,
                'telat'             => 60,
            ];
    
            // Definisikan rules sekali saja di sini
            $rules = [
                ['sedikit', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
                ['banyak', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
                ['sedang', 'sedang', 'normal', 'sesuai_deadline'],
                ['sedikit', 'tinggi', 'penting', 'telat'],
                ['sedikit', 'tinggi', 'super_penting', 'telat'],
                ['banyak', 'tinggi', 'super_penting', 'telat'],
                ['banyak', 'rendah', 'super_penting', 'telat'],
                ['sedikit', 'tinggi', 'bisa_didahulukan', 'sesuai_deadline'],
                ['sedang', 'tinggi', 'normal', 'telat'],
                ['banyak', 'tinggi', 'normal', 'sesuai_deadline'],
                ['sedang', 'tinggi', 'penting', 'telat'],
                ['banyak', 'tinggi', 'penting', 'telat']
            ];
    
            $results = [];
    
            foreach ($projects as $project) {
                try {
                    // Fuzzifikasi: Membership function untuk setiap input
                    $employeeMembership = [
                        'sedikit' => $this->calculateMembership($project->employee_count, 1, 2, 3),
                        'sedang'  => $this->calculateMembership($project->employee_count, 2, 3, 4),
                        'banyak'  => $this->calculateMembership($project->employee_count, 3, 4, 5)
                    ];
    
                    $workingHoursMembership = [
                        'rendah' => $this->calculateMembership($project->working_hours, 5, 20, 35),
                        'sedang' => $this->calculateMembership($project->working_hours, 30, 40, 50),
                        'tinggi' => $this->calculateMembership($project->working_hours, 45, 50, 56)
                    ];
    
                    $priorityMembership = [
                        'bisa_didahulukan' => $this->calculateMembership($project->priority_scale, 0, 1, 2),
                        'normal' => $this->calculateMembership($project->priority_scale, 1, 2, 3),
                        'penting' => $this->calculateMembership($project->priority_scale, 2, 3, 4),
                        'super_penting' => $this->calculateMembership($project->priority_scale, 3, 4, 4)
                    ];
    
                    // Inferensi & Defuzzifikasi dengan metode Tsukamoto (rata-rata tertimbang)
                    $zSum = 0;
                    $alphaSum = 0;
                    $alphas = [];
    
                    // Aturan fuzzy yang lebih lengkap dan spesifik
                    $rules = [
                        ['sedikit', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
                        ['banyak', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
                        ['sedang', 'sedang', 'normal', 'sesuai_deadline'],
                        ['sedikit', 'tinggi', 'penting', 'telat'],
                        ['sedikit', 'tinggi', 'super_penting', 'telat'],
                        ['banyak', 'tinggi', 'super_penting', 'telat'],
                        ['banyak', 'rendah', 'super_penting', 'telat'],
                        ['sedikit', 'tinggi', 'bisa_didahulukan', 'sesuai_deadline'],
                        ['sedang', 'tinggi', 'normal', 'telat'],
                        ['banyak', 'tinggi', 'normal', 'sesuai_deadline'],
                        ['sedang', 'tinggi', 'penting', 'telat'],
                        ['banyak', 'tinggi', 'penting', 'telat'],
                        // Tambahan rules untuk working hours tinggi
                        ['banyak', 'tinggi', 'bisa_didahulukan', 'telat'],
                        ['banyak', 'tinggi', 'super_penting', 'telat']
                    ];
    
                    foreach ($rules as $rule) {
                        [$e, $w, $p, $outLabel] = $rule;
    
                        $alpha = min(
                            $employeeMembership[$e] ?? 0,
                            $workingHoursMembership[$w] ?? 0,
                            $priorityMembership[$p] ?? 0
                        );
    
                        $alphas[] = [
                            'rule'  => $rule,
                            'alpha' => $alpha,
                            'z'     => $zMapping[$outLabel]
                        ];
    
                        if ($alpha > 0) {
                            $z = $zMapping[$outLabel];
                            $zSum += $alpha * $z;
                            $alphaSum += $alpha;
                        }
                    }
    
                    // Perbaikan logika default processing time
                    if ($alphaSum == 0) {
                        $processingTime = $this->getDefaultProcessingTime(
                            $employeeMembership,
                            $workingHoursMembership,
                            $priorityMembership
                        );
                    } else {
                        $processingTime = $zSum / $alphaSum;
                    }
    
                    // Add overtime decision
                    $overtimeStatus = $this->getOvertimeDecision($processingTime);
    
                    $results[] = [
                        'project' => $project,
                        'scores' => [
                            'employee'       => $employeeMembership,
                            'working_hours'  => $workingHoursMembership,
                            'priority'       => $priorityMembership,
                            'alphas'         => $alphas,
                            'processing_time' => round($processingTime),
                            'overtime_status' => $overtimeStatus
                        ],
                    ];
                } catch (\Exception $e) {
                    \Log::error("Error processing project ID {$project->id}: " . $e->getMessage());
                    continue;
                }
            }
    
            return view('projects.fuzzy-analysis', compact('projects', 'results'));
    
        } catch (\Exception $e) {
            \Log::error("Fuzzy Analysis Error: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    


    private function calculateMembership($x, $a, $b, $c)
    {
        // Khusus untuk nilai maksimum
        if ($x == $c) {
            if ($c == 5 || $c == 56 || $c == 4) {
                return 1;
            }
        }
        
        if ($x <= $a) return 0;
        if ($x >= $c) return 0;
        if ($x == $b) return 1;
        if ($x < $b) return ($x - $a) / ($b - $a);
        return ($c - $x) / ($c - $b);
    }

    public function create()
    {
        $divisions = Division::all();
        return view('projects.create', compact('divisions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
            'deadline' => 'required|date',
            'employee_count' => 'required|integer|min:1|max:5',
            'working_hours' => 'required|integer|min:5|max:56',
            'priority_scale' => 'required|integer|min:1|max:4',
        ]);
    
        try {
            $project = Project::create($validated);
            
            // Hitung fuzzy dan update processing time
            $fuzzyResult = $this->calculateFuzzyForProject($project);
            $project->update(['processing_time' => round($fuzzyResult)]);
            
            return redirect()->route('projects.index')
                ->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to create project: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Failed to create project. ' . $e->getMessage());
        }
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
            'deadline' => 'required|date',
            'employee_count' => 'required|integer|min:1|max:5',
            'working_hours' => 'required|integer|min:5|max:56',
            'priority_scale' => 'required|integer|min:1|max:4',
            'priority_level' => 'required|integer|min:1|max:4'
        ]);
    
        $project->update($validated);
        
        // Hitung ulang fuzzy setelah update
        $fuzzyResult = $this->calculateFuzzyForProject($project);
        $project->update(['processing_time' => round($fuzzyResult)]);
        
        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    // Tambahkan method baru untuk menghitung fuzzy
    private function calculateFuzzyForProject($project)
    {
        $employeeMembership = [
            'sedikit' => $this->calculateMembership($project->employee_count, 1, 2, 3),
            'sedang'  => $this->calculateMembership($project->employee_count, 2, 3, 4),
            'banyak'  => $this->calculateMembership($project->employee_count, 3, 4, 5)
        ];
    
        $workingHoursMembership = [
            'rendah' => $this->calculateMembership($project->working_hours, 5, 20, 35),
            'sedang' => $this->calculateMembership($project->working_hours, 30, 40, 50),
            'tinggi' => $this->calculateMembership($project->working_hours, 45, 50, 56)
        ];
    
        $priorityMembership = [
            'bisa_didahulukan' => $this->calculateMembership($project->priority_scale, 0, 1, 2),
            'normal' => $this->calculateMembership($project->priority_scale, 1, 2, 3),
            'penting' => $this->calculateMembership($project->priority_scale, 2, 3, 4),
            'super_penting' => $this->calculateMembership($project->priority_scale, 3, 4, 4)
        ];
    
        // Pindahkan pengecekan kasus khusus ke sini
        if ($workingHoursMembership['tinggi'] > 0 && $priorityMembership['super_penting'] > 0) {
            return 60;
        }
    
        $zMapping = [
            'selesai_cepat'   => 40,
            'sesuai_deadline' => 50,
            'telat'           => 60,
        ];
    
        // Gunakan rules yang sama dengan fuzzyAnalysis
        $rules = [
            ['sedikit', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
            ['banyak', 'rendah', 'bisa_didahulukan', 'selesai_cepat'],
            ['sedang', 'sedang', 'normal', 'sesuai_deadline'],
            ['sedikit', 'tinggi', 'penting', 'telat'],
            ['sedikit', 'tinggi', 'super_penting', 'telat'],
            ['banyak', 'tinggi', 'super_penting', 'telat'],
            ['banyak', 'rendah', 'super_penting', 'telat'],
            ['sedikit', 'tinggi', 'bisa_didahulukan', 'sesuai_deadline'],
            ['sedang', 'tinggi', 'normal', 'telat'],
            ['banyak', 'tinggi', 'normal', 'sesuai_deadline'],
            ['sedang', 'tinggi', 'penting', 'telat'],
            ['banyak', 'tinggi', 'penting', 'telat'],
            ['banyak', 'tinggi', 'bisa_didahulukan', 'telat'],
            ['banyak', 'tinggi', 'super_penting', 'telat']
        ];
    
        $zSum = 0;
        $alphaSum = 0;
    
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
    
        // Untuk kasus khusus working hours tinggi dan priority super penting
        // Pindahkan pengecekan ini ke awal fungsi setelah menghitung membership
        if ($workingHoursMembership['tinggi'] > 0 && $priorityMembership['super_penting'] > 0) {
            return 60;  // Pastikan selalu return 60 untuk kasus ini
        }
    
        if ($alphaSum == 0) {
            return $this->getDefaultProcessingTime($employeeMembership, $workingHoursMembership, $priorityMembership);
        }
        
        return $zSum / $alphaSum;
    }

    public function show(Project $project)
    {
        $project->load('division'); // Eager load the division relationship
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $divisions = Division::all();
        return view('projects.edit', compact('project', 'divisions'));
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }

    // Add the method inside the class
    private function getOvertimeDecision($processingTime)
    {
        if ($processingTime <= 40) {
            return 'Tidak Perlu Lembur';
        } elseif ($processingTime <= 50) {
            return 'Lembur Sedang';
        } else {
            return 'Perlu Lembur';
        }
    }

    // Tambah method baru
    private function getDefaultProcessingTime($emp, $hours, $priority) 
    {
        if ($hours['tinggi'] > 0) {
            if ($priority['super_penting'] > 0 || $priority['penting'] > 0) {
                return 60;  // Sesuaikan dengan hasil fuzzy
            }
            return 60; // Ubah dari 55 ke 60 untuk konsistensi
        }
        if ($priority['super_penting'] > 0) {
            return 60;
        }
        if ($emp['banyak'] > 0 && $hours['rendah'] > 0) {
            return 40;
        }
        return 45; // Default value yang lebih masuk akal
    }
}
