<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium">{{ $project->name }}</h3>
                        <p class="text-gray-600">Client: {{ $project->client_name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="font-medium">Division</p>
                            <p>{{ $project->division->name }}</p>
                        </div>
                        <div>
                            <p class="font-medium">Deadline</p>
                            <p>{{ $project->deadline->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="font-medium">Employee Count</p>
                            <p>{{ $project->employee_count }}</p>
                        </div>
                        <div>
                            <p class="font-medium">Working Hours</p>
                            <p>{{ $project->working_hours }}</p>
                        </div>
                        <div>
                            <p class="font-medium">Priority Level</p>
                            <p>{{ $project->priority_scale }}</p>
                        </div>
                        <div>
                            <p class="font-medium">Processing Time</p>
                            <p>{{ $project->processing_time }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Projects
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>