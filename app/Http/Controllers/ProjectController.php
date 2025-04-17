<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FuzzyTsukamotoService;

class ProjectController extends Controller
{
    protected $fuzzyService;

    public function __construct(FuzzyTsukamotoService $fuzzyService)
    {
        $this->fuzzyService = $fuzzyService;
    }

    public function index()
    {
        $projects = Project::with(['division', 'assignedUser'])->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $divisions = Division::all();
        $users = User::all();
        return view('projects.create', compact('divisions', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'client_name' => 'required',
            'division_id' => 'required|exists:divisions,id',
            'deadline' => 'required|date',
            'difficulty_level' => 'required|integer|between:1,5',
            'priority_level' => 'required|integer|between:1,5',
            'processing_time' => 'required|integer|min:1',
            'assigned_to' => 'required|exists:users,id'
        ]);
        // dd($validated);
        Project::create($validated);  // This should now work with division_id

        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    public function edit(Project $project)
    {
        $divisions = Division::all();
        $users = User::all();
        return view('projects.edit', compact('project', 'divisions', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required',
            'client_name' => 'required',
            'division_id' => 'required|exists:divisions,id',
            'deadline' => 'required|date',
            'difficulty_level' => 'required|integer|between:1,5',
            'priority_level' => 'required|integer|between:1,5',
            'processing_time' => 'required|integer|min:1',
            'assigned_to' => 'required|exists:users,id'
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }

    public function show(Project $project)
    {
        $overtimeProbability = $this->fuzzyService->calculateOvertimeProbability(
            $project->processing_time,
            $project->priority_level,
            $project->difficulty_level
        );

        return view('projects.show', compact('project', 'overtimeProbability'));
    }
}
