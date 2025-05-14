<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $projects = Project::with('division')->get();
        
        return view('dashboard', [
            'projects' => $projects
        ]);
    }
}