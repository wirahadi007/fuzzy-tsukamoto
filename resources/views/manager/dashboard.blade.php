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

                <!-- Project Table -->
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Processing Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Deadline</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($projects as $project)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->client_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->division->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->employee_count }} people</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $project->processing_time }} hours</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
            </div>
        </div>
    </div>
</div>
@endsection