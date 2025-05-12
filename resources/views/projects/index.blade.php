@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Projects List</h2>
                    <a href="{{ route('projects.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add New Project
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center cursor-pointer" onclick="sortTable(0, 'text')">
                                        Name <span class="sort-icon ml-1">↕️</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center cursor-pointer" onclick="sortTable(1, 'text')">
                                        Client <span class="sort-icon ml-1">↕️</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center cursor-pointer" onclick="sortTable(2, 'text')">
                                        Division <span class="sort-icon ml-1">↕️</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center cursor-pointer" onclick="sortTable(3, 'date')">
                                        Deadline <span class="sort-icon ml-1">↕️</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="projectTableBody" class="bg-white divide-y divide-gray-200">
                            @foreach($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $project->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $project->client_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $project->division->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $project->deadline }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('projects.edit', $project) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
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

@push('scripts')
<script>
    let sortStates = {
        0: 'none', // Name
        1: 'none', // Client
        2: 'none', // Division
        3: 'none'  // Deadline
    };

    function sortTable(columnIndex, type) {
        const tbody = document.getElementById('projectTableBody');
        const rows = Array.from(tbody.getElementsByTagName('tr'));
        const icons = document.getElementsByClassName('sort-icon');
        
        // Reset other column icons
        Array.from(icons).forEach((icon, index) => {
            if (index !== columnIndex) {
                icon.textContent = '↕️';
            }
        });

        // Update sort state
        if (sortStates[columnIndex] === 'none' || sortStates[columnIndex] === 'desc') {
            sortStates[columnIndex] = 'asc';
            icons[columnIndex].textContent = '↑';
        } else {
            sortStates[columnIndex] = 'desc';
            icons[columnIndex].textContent = '↓';
        }

        rows.sort((a, b) => {
            let valueA = a.cells[columnIndex].textContent.trim();
            let valueB = b.cells[columnIndex].textContent.trim();

            if (type === 'date') {
                valueA = new Date(valueA);
                valueB = new Date(valueB);
            }

            if (sortStates[columnIndex] === 'asc') {
                if (type === 'text') {
                    return valueA.localeCompare(valueB);
                }
                return valueA - valueB;
            } else {
                if (type === 'text') {
                    return valueB.localeCompare(valueA);
                }
                return valueB - valueA;
            }
        });

        rows.forEach(row => tbody.appendChild(row));
    }
</script>
@endpush
@endsection