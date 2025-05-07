<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Pastikan data diambil dengan eager loading
        $projects = Project::with('division')->get();
        
        // Debug untuk memastikan data ada
        \Log::info('Projects count: ' . $projects->count());
        
        return view('dashboard', compact('projects'));
    }
}