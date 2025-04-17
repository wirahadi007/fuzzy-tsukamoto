<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(auth()->user()->hasRole('admin'))
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
</x-app-layout>
