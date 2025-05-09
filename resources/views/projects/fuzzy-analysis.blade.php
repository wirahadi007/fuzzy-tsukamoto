@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="font-bold mb-4">Project Details</h3>
                <div class="overflow-x-auto overflow-y-auto max-h-screen"> <!-- Added overflow-y and max height -->
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">Project Name</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Employee Count</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Working Hours</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Priority Scale</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Processing Time</th>
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
                                    <td class="px-6 py-4">{{ $project->processing_time }}</td>
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
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleFuzzyAnalysis(projectId) {
        const fuzzyRow = document.getElementById(`fuzzy-${projectId}`);
        if (fuzzyRow) {
            fuzzyRow.classList.toggle('hidden');
        }
    }
</script>
@endpush
@endsection
