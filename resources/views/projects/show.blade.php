@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Project Details</h2>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Project Information</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Project Name</label>
                                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Client</label>
                                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->client_name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Division</label>
                                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->division->name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Deadline</label>
                                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->deadline->format('Y-m-d') }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Overtime Analysis</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Processing Time</label>
                                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->processing_time }} days</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Priority Level</label>
                                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->priority_level }}/5</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Difficulty Level</label>
                                <div class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->difficulty_level }}/5</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Overtime Probability</label>
                                <div class="mt-1">
                                    <div class="text-lg font-semibold 
                                        {{ $overtimeProbability >= 75 ? 'text-red-600' : 
                                           ($overtimeProbability >= 50 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ number_format($overtimeProbability, 2) }}%
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500">
                                        @if($overtimeProbability >= 75)
                                            High probability of requiring overtime
                                        @elseif($overtimeProbability >= 50)
                                            Moderate probability of requiring overtime
                                        @else
                                            Low probability of requiring overtime
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to Projects
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection