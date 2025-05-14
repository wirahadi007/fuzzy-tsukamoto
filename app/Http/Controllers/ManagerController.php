<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function dashboard()
    {
        $projects = Project::with('division')->get();
        return view('manager.dashboard', compact('projects'));
    }
}