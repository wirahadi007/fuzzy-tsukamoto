@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(auth()->user()->hasRole('admin'))
                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Total Projects</h4>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $projects->count() }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Need Overtime</h4>
                                <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                    {{ $projects->where('processing_time', '>', 50)->count() }}
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Normal Schedule</h4>
                                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                    {{ $projects->whereBetween('processing_time', [41, 50])->count() }}
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">On Track</h4>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ $projects->where('processing_time', '<=', 40)->count() }}
                                </p>
                            </div>
                        </div>

                        <!-- Charts Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Overtime Distribution Chart -->
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4" style="min-height: 400px;">
                                <h4 class="text-gray-900 dark:text-gray-100 font-semibold mb-4">Overtime Distribution</h4>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="overtimeChart"></canvas>
                                </div>
                            </div>
                            
                            <!-- Priority Distribution Chart -->
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4" style="min-height: 400px;">
                                <h4 class="text-gray-900 dark:text-gray-100 font-semibold mb-4">Project Priority Distribution</h4>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="priorityChart"></canvas>
                                </div>
                            </div>
                        </div>

                        @push('scripts')
                        <script>
                            // Chart configurations
                            const chartOptions = {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            color: document.querySelector('html').classList.contains('dark') ? '#fff' : '#000'
                                        }
                                    }
                                }
                            };
                        
                            // Overtime Distribution Chart
                            new Chart(overtimeCtx, {
                                type: 'pie',
                                data: {
                                    labels: ['Perlu Lembur', 'Lembur Sedang', 'Tidak Perlu Lembur'],
                                    datasets: [{
                                        data: [
                                            {{ $projects->filter(function($p) { 
                                                return $p->processing_time >= 50 && $p->priority_level == 3; 
                                            })->count() }},
                                            {{ $projects->filter(function($p) { 
                                                return $p->processing_time >= 40 && $p->processing_time < 50 && $p->priority_level == 2; 
                                            })->count() }},
                                            {{ $projects->filter(function($p) { 
                                                return $p->processing_time < 40 && $p->priority_level == 1; 
                                            })->count() }}
                                        ],
                                        backgroundColor: ['#EF4444', '#F59E0B', '#10B981']
                                    }]
                                },
                                options: {
                                    ...chartOptions,
                                    layout: {
                                        padding: {
                                            bottom: 20
                                        }
                                    }
                                }
                            });
                        
                            // Priority Distribution Chart
                            new Chart(priorityCtx, {
                                type: 'bar',
                                data: {
                                    labels: ['Bisa Didahulukan', 'Normal', 'Penting', 'Super Penting'],
                                    datasets: [{
                                        label: 'Jumlah Project',
                                        data: [
                                            {{ $projects->filter(function($p) { return $p->priority_scale == 1; })->count() }},
                                            {{ $projects->filter(function($p) { return $p->priority_scale == 2; })->count() }},
                                            {{ $projects->filter(function($p) { return $p->priority_scale == 3; })->count() }},
                                            {{ $projects->filter(function($p) { return $p->priority_scale == 4; })->count() }}
                                        ],
                                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#B91C1C']
                                    }]
                                },
                                options: {
                                    ...chartOptions,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1,
                                                color: document.querySelector('html').classList.contains('dark') ? '#fff' : '#000'
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                color: document.querySelector('html').classList.contains('dark') ? '#fff' : '#000'
                                            }
                                        }
                                    }
                                }
                            });
                        </script>
                        @endpush
                        
                        <!-- Existing Project Table -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Project Management</h3>
                                <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Create New Project
                                </a>
                            </div>
                            
                            <div class="mt-6 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Project</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Client</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Division</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Team Size</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Processing Time (Hours)</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($projects ?? [] as $project)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->client_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->division->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->employee_count }} people</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->processing_time }} hours</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                @if($project->priority_level == 1)
                                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Bisa Didahulukan</span>
                                                @elseif($project->priority_level == 2)
                                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Normal</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Super Penting</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->deadline->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
                                                <a href="{{ route('projects.edit', $project) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</a>
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @elseif(auth()->user()->hasRole('accounting'))
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Project Monitoring</h3>
                            <div class="mt-6">
                                <!-- Add your accounting view here -->
                            </div>
                        </div>
                    @elseif(auth()->user()->hasRole('manager'))
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Project Overview</h3>
                            <div class="mt-6">
                                <!-- Add your manager view here -->
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Get canvas elements
        const overtimeCtx = document.getElementById('overtimeChart').getContext('2d');
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');

        // Overtime Distribution Chart
        new Chart(overtimeCtx, {
            type: 'pie',
            data: {
                labels: ['Perlu Lembur', 'Lembur Sedang', 'Tidak Perlu Lembur'],
                datasets: [{
                    data: [
                        {{ $projects->filter(function($p) { 
                            return $p->processing_time >= 50 && $p->priority_level == 3; 
                        })->count() }},
                        {{ $projects->filter(function($p) { 
                            return $p->processing_time >= 40 && $p->processing_time < 50 && $p->priority_level == 2; 
                        })->count() }},
                        {{ $projects->filter(function($p) { 
                            return $p->processing_time < 40 && $p->priority_level == 1; 
                        })->count() }}
                    ],
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981']
                }]
            },
            options: chartOptions
        });

        // Priority Distribution Chart
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: ['Bisa Didahulukan', 'Normal', 'Super Penting'],
                datasets: [{
                    label: 'Jumlah Project',
                    data: [
                        {{ $projects->where('priority_level', 1)->count() }},
                        {{ $projects->where('priority_level', 2)->count() }},
                        {{ $projects->where('priority_level', 3)->count() }}
                    ],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: document.querySelector('html').classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                }
            }
        });
    </script>
@endpush
