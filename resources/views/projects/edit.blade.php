@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Edit Project</h2>

                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name</label>
                        <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $project->client_name) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="division_id" class="block text-sm font-medium text-gray-700">Division</label>
                        <select name="division_id" id="division_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" 
                                    {{ old('division_id', $project->division_id) == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="employee_count" class="block text-sm font-medium text-gray-700">Number of Employees (1-5)</label>
                        <input type="number" name="employee_count" id="employee_count" min="1" max="5" 
                               value="{{ old('employee_count', $project->employee_count) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="working_hours" class="block text-sm font-medium text-gray-700">Working Hours (5-56)</label>
                        <input type="number" name="working_hours" id="working_hours" min="5" max="56" 
                               value="{{ old('working_hours', $project->working_hours) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="priority_scale" class="block text-sm font-medium text-gray-700">Priority Level (1-4)</label>
                        <input type="number" name="priority_scale" id="priority_scale" min="1" max="4" 
                               value="{{ old('priority_scale', $project->priority_scale) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="mb-4">
                        <label for="deadline" class="block text-sm font-medium text-gray-700">Deadline</label>
                        <input type="date" name="deadline" id="deadline" 
                               value="{{ old('deadline', $project->deadline) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection