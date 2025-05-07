@extends('layouts.app')

@section('content')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Projects Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900">
                <h3 class="font-bold mb-4">Project Details</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left">Project Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left">Employee Count</th>
                            <th class="px-6 py-3 bg-gray-50 text-left">Working Hours</th>
                            <th class="px-6 py-3 bg-gray-50 text-left">Priority Scale</th>
                            <th class="px-6 py-3 bg-gray-50 text-left">Processing Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            <tr>
                                <td class="px-6 py-4">{{ $project->name }}</td>
                                <td class="px-6 py-4">{{ $project->employee_count }}</td>
                                <td class="px-6 py-4">{{ $project->working_hours }}</td>
                                <td class="px-6 py-4">{{ $project->priority_scale }}</td>
                                <td class="px-6 py-4">{{ $project->processing_time }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center">No projects found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Fuzzy Analysis Results -->
        @if(!empty($results))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold mb-4">Fuzzy Analysis Details</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left">Project</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Employee Membership</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Working Hours Membership</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Priority Membership</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Alpha</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Processing Time</th>
                                <th class="px-6 py-3 bg-gray-50 text-left">Status Lembur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="px-6 py-4">{{ $result['project']->name }}</td>
                                    <td class="px-6 py-4">
                                        Sedikit: {{ number_format($result['scores']['employee']['sedikit'], 2) }}<br>
                                        Sedang: {{ number_format($result['scores']['employee']['sedang'], 2) }}<br>
                                        Banyak: {{ number_format($result['scores']['employee']['banyak'], 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        Rendah: {{ number_format($result['scores']['working_hours']['rendah'], 2) }}<br>
                                        Sedang: {{ number_format($result['scores']['working_hours']['sedang'], 2) }}<br>
                                        Tinggi: {{ number_format($result['scores']['working_hours']['tinggi'], 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        Bisa Didahulukan: {{ number_format($result['scores']['priority']['bisa_didahulukan'], 2) }}<br>
                                        Normal: {{ number_format($result['scores']['priority']['normal'], 2) }}<br>
                                        Super Penting: {{ number_format($result['scores']['priority']['super_penting'], 2) }}<br>
                                        Penting: {{ number_format($result['scores']['priority']['penting'], 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach($result['scores']['alphas'] as $alpha)
                                            ({{ $alpha['rule'][0] }}, {{ $alpha['rule'][1] }}, 
                                             {{ $alpha['rule'][2] }}, {{ $alpha['rule'][3] }}) 
                                            → α = {{ number_format($alpha['alpha'], 2) }}, 
                                            z = {{ $alpha['z'] }}<br>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">{{ $result['scores']['processing_time'] }}</td>
                                    <td class="px-6 py-4">{{ $result['scores']['overtime_status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
