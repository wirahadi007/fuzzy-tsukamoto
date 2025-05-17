@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                        <!-- Statistics Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Total Projects</h4>
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $projects->count() }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Perlu Lembur</h4>
                                <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                    {{ $projects->where('processing_time', '>', 50)->count() }}
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Lembur Sedang</h4>
                                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                    {{ $projects->whereBetween('processing_time', [41, 50])->count() }}
                                </p>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                                <h4 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">Tidak Perlu Lembur</h4>
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
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                <div class="flex items-center cursor-pointer" onclick="sortTable()">
                                                    Deadline
                                                    <span id="sort-icon" class="ml-1">↕️</span>
                                                </div>
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="projectTableBody" class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($projects ?? [] as $project)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->client_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->division->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->employee_count }} people</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->processing_time }} hours</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">
                                                @if($project->priority_scale == 1)
                                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Bisa Didahulukan</span>
                                                @elseif($project->priority_scale == 2)
                                                    <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Normal</span>
                                                @elseif($project->priority_scale == 3)
                                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Penting</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Super Penting</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->deadline->format('Y-m-d') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                                                    <a href="{{ route('projects.edit', $project) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</a>
                                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
    <script>
        let sortDirection = 'asc';
        
        function sortTable() {
            const tbody = document.getElementById('projectTableBody');
            const rows = Array.from(tbody.getElementsByTagName('tr'));
            const icon = document.getElementById('sort-icon');
            
            rows.sort((a, b) => {
                const dateA = new Date(a.cells[6].textContent.trim());
                const dateB = new Date(b.cells[6].textContent.trim());
                
                if (sortDirection === 'asc') {
                    icon.textContent = '↓';
                    return dateA - dateB;
                } else {
                    icon.textContent = '↑';
                    return dateB - dateA;
                }
            });
            
            rows.forEach(row => tbody.appendChild(row));
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        }
    </script>
@endpush
@push('scripts')
<script>
    let sortDirection = 'asc';
    
    function sortTable() {
        const tbody = document.getElementById('projectTableBody');
        const rows = Array.from(tbody.getElementsByTagName('tr'));
        const icon = document.getElementById('sort-icon');
        
        rows.sort((a, b) => {
            const dateA = new Date(a.cells[6].textContent.trim());
            const dateB = new Date(b.cells[6].textContent.trim());
            
            if (sortDirection === 'asc') {
                icon.textContent = '↓';
                return dateA - dateB;
            } else {
                icon.textContent = '↑';
                return dateB - dateA;
            }
        });
        
        rows.forEach(row => tbody.appendChild(row));
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
    }
</script>
@endpush


<!-- Project List Modal -->
<div id="projectListModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modalTitle"></h3>
            <div class="mt-2 max-h-[60vh] overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Division</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Processing Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Deadline</th>
                        </tr>
                    </thead>
                    <tbody id="modalProjectList" class="bg-white divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="closeProjectListModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showProjectListModal(title, projects) {
        const modal = document.getElementById('projectListModal');
        const modalTitle = document.getElementById('modalTitle');
        const projectList = document.getElementById('modalProjectList');
        
        modalTitle.textContent = title;
        projectList.innerHTML = '';
        
        projects.forEach(project => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">${project.name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">${project.client_name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">${project.division.name}</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">${project.processing_time} hours</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">${new Date(project.deadline).toLocaleDateString()}</td>
            `;
            projectList.appendChild(row);
        });
        
        modal.classList.remove('hidden');
    }

    function closeProjectListModal() {
        const modal = document.getElementById('projectListModal');
        modal.classList.add('hidden');
    }
</script>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const overtimeCtx = document.getElementById('overtimeChart').getContext('2d');
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');

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
                        {{ $projects->where('processing_time', '>', 50)->count() }},
                        {{ $projects->whereBetween('processing_time', [41, 50])->count() }},
                        {{ $projects->where('processing_time', '<=', 40)->count() }}
                    ],
                    backgroundColor: ['#EF4444', '#F59E0B', '#10B981']
                }]
            },
            options: {
                ...chartOptions,
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        let projects = [];
                        let title = '';
                        
                        switch(index) {
                            case 0:
                                title = 'Projects - Perlu Lembur';
                                projects = {{ Illuminate\Support\Js::from($projects->where('processing_time', '>', 50)->values()) }};
                                break;
                            case 1:
                                title = 'Projects - Lembur Sedang';
                                projects = {{ Illuminate\Support\Js::from($projects->whereBetween('processing_time', [41, 50])->values()) }};
                                break;
                            case 2:
                                title = 'Projects - Tidak Perlu Lembur';
                                projects = {{ Illuminate\Support\Js::from($projects->where('processing_time', '<=', 40)->values()) }};
                                break;
                        }
                        
                        showProjectListModal(title, projects);
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
                        {{ $projects->where('priority_scale', 1)->count() }},
                        {{ $projects->where('priority_scale', 2)->count() }},
                        {{ $projects->where('priority_scale', 3)->count() }},
                        {{ $projects->where('priority_scale', 4)->count() }}
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
                    }
                },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        let projects = [];
                        let title = '';
                        
                        switch(index) {
                            case 0:
                                title = 'Projects - Bisa Didahulukan';
                                projects = {{ Illuminate\Support\Js::from($projects->where('priority_scale', 1)->values()) }};
                                break;
                            case 1:
                                title = 'Projects - Normal';
                                projects = {{ Illuminate\Support\Js::from($projects->where('priority_scale', 2)->values()) }};
                                break;
                            case 2:
                                title = 'Projects - Penting';
                                projects = {{ Illuminate\Support\Js::from($projects->where('priority_scale', 3)->values()) }};
                                break;
                            case 3:
                                title = 'Projects - Super Penting';
                                projects = {{ Illuminate\Support\Js::from($projects->where('priority_scale', 4)->values()) }};
                                break;
                        }
                        
                        showProjectListModal(title, projects);
                    }
                }
            }
        });
    });
</script>
@endpush
