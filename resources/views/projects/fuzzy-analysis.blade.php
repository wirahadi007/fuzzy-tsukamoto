@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="font-bold mb-4">Project Details</h3>
                @if(isset($projects) && count($projects) > 0)
                    <div class="overflow-x-auto overflow-y-auto max-h-screen">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left cursor-pointer" onclick="sortTable(0)">Project Name <span id="sort-0" class="sort-indicator"></span></th>
                                    <th class="px-6 py-3 bg-gray-50 text-left cursor-pointer" onclick="sortTable(1)">Employee Count <span id="sort-1" class="sort-indicator"></span></th>
                                    <th class="px-6 py-3 bg-gray-50 text-left cursor-pointer" onclick="sortTable(2)">Working Hours <span id="sort-2" class="sort-indicator"></span></th>
                                    <th class="px-6 py-3 bg-gray-50 text-left cursor-pointer" onclick="sortTable(3)">Priority Scale <span id="sort-3" class="sort-indicator"></span></th>
                                    <th class="px-6 py-3 bg-gray-50 text-left cursor-pointer" onclick="sortTable(4)">Processing Time <span id="sort-4" class="sort-indicator"></span></th>
                                    <th class="px-6 py-3 bg-gray-50 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $key => $project)
                                    <tr>
                                        <td class="px-6 py-4">{{ $project->name }}</td>
                                        <td class="px-6 py-4">{{ $project->employee_count }}</td>
                                        <td class="px-6 py-4">{{ $project->working_hours }}</td>
                                        <td class="px-6 py-4">{{ $project->priority_scale }}</td>
                                        <td class="px-6 py-4">{{ $results[$key]['scores']['processing_time'] }}</td>
                                        <td class="px-6 py-4">
                                            @if(isset($results[$key]))
                                                <button onclick="toggleFuzzyAnalysis({{ $project->id }})" 
                                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    View Analysis
                                                </button>
                                            @else
                                                <span class="text-red-500 text-sm">No Analysis</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if(isset($results[$key]))
                                        <tr id="fuzzy-{{ $project->id }}" class="hidden bg-gray-50">
                                            <td colspan="6" class="px-6 py-4">
                                                <div class="space-y-4">
                                                    <h4 class="font-semibold">Fuzzy Analysis for {{ $project->name }}</h4>
                                                    <div class="grid grid-cols-3 gap-4">
                                                        <div>
                                                            <h5 class="font-medium mb-2">Employee Membership</h5>
                                                            <p>Sedikit: {{ number_format($results[$key]['scores']['employee']['sedikit'], 2) }}</p>
                                                            <p>Sedang: {{ number_format($results[$key]['scores']['employee']['sedang'], 2) }}</p>
                                                            <p>Banyak: {{ number_format($results[$key]['scores']['employee']['banyak'], 2) }}</p>
                                                        </div>
                                                        <div>
                                                            <h5 class="font-medium mb-2">Working Hours Membership</h5>
                                                            <p>Rendah: {{ number_format($results[$key]['scores']['working_hours']['rendah'], 2) }}</p>
                                                            <p>Sedang: {{ number_format($results[$key]['scores']['working_hours']['sedang'], 2) }}</p>
                                                            <p>Tinggi: {{ number_format($results[$key]['scores']['working_hours']['tinggi'], 2) }}</p>
                                                        </div>
                                                        <div>
                                                            <h5 class="font-medium mb-2">Priority Membership</h5>
                                                            <p>Bisa Didahulukan: {{ number_format($results[$key]['scores']['priority']['bisa_didahulukan'], 2) }}</p>
                                                            <p>Normal: {{ number_format($results[$key]['scores']['priority']['normal'], 2) }}</p>
                                                            <p>Super Penting: {{ number_format($results[$key]['scores']['priority']['super_penting'], 2) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4">
                                                        <h5 class="font-medium mb-2">Alpha Rules</h5>
                                                        @foreach($results[$key]['scores']['alphas'] as $alpha)
                                                            <p>({{ $alpha['rule'][0] }}, {{ $alpha['rule'][1] }}, 
                                                               {{ $alpha['rule'][2] }}, {{ $alpha['rule'][3] }}) 
                                                               → α = {{ number_format($alpha['alpha'], 2) }}, 
                                                               z = {{ $alpha['z'] }}</p>
                                                        @endforeach
                                                    </div>
                                                    <div class="mt-4">
                                                        <h5 class="font-medium mb-2">Final Results</h5>
                                                        <p>Processing Time: {{ $results[$key]['scores']['processing_time'] }}</p>
                                                        <p>Status Lembur: {{ $results[$key]['scores']['overtime_status'] }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center">No projects found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Data Project Kosong</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada project yang tersedia untuk analisis fuzzy.</p>
                        <div class="mt-6">
                            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Kembali ke Daftar Project
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .sort-indicator::after {
        content: '⇅';
        margin-left: 5px;
        opacity: 0.5;
    }
    .sort-asc::after {
        content: '↑';
        opacity: 1;
    }
    .sort-desc::after {
        content: '↓';
        opacity: 1;
    }
</style>
<script>
    function toggleFuzzyAnalysis(projectId) {
        const fuzzyRow = document.getElementById(`fuzzy-${projectId}`);
        if (fuzzyRow) {
            fuzzyRow.classList.toggle('hidden');
        }
    }

    let currentSort = {
        column: -1,
        asc: true
    };

    function sortTable(column) {
        const table = document.querySelector('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr:not([id^="fuzzy-"])'));
        
        // Reset all sort indicators
        document.querySelectorAll('.sort-indicator').forEach(indicator => {
            indicator.className = 'sort-indicator';
        });

        // Update sort direction
        if (currentSort.column === column) {
            currentSort.asc = !currentSort.asc;
        } else {
            currentSort.column = column;
            currentSort.asc = true;
        }

        // Update sort indicator
        const indicator = document.getElementById(`sort-${column}`);
        indicator.className = `sort-indicator ${currentSort.asc ? 'sort-asc' : 'sort-desc'}`;

        // Sort rows
        rows.sort((a, b) => {
            let aValue = a.cells[column].textContent.trim();
            let bValue = b.cells[column].textContent.trim();

            // Handle numeric columns
            if (column === 1 || column === 2 || column === 3 || column === 4) {
                aValue = parseFloat(aValue) || 0;
                bValue = parseFloat(bValue) || 0;
                return currentSort.asc ? aValue - bValue : bValue - aValue;
            }

            // Handle text columns
            return currentSort.asc 
                ? aValue.localeCompare(bValue)
                : bValue.localeCompare(aValue);
        });

        // Reorder rows
        rows.forEach(row => {
            tbody.appendChild(row);
            // Move associated fuzzy analysis row if exists
            const fuzzyRow = document.getElementById(`fuzzy-${row.querySelector('button')?.getAttribute('onclick')?.match(/\d+/)?.[0]}`);
            if (fuzzyRow) {
                tbody.appendChild(fuzzyRow);
            }
        });
    }
</script>
@endpush
@endsection
