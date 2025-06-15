@extends('layouts.app')

@section('title', 'Fuzzy Analysis')

@section('content')
<div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Overtime Analysis Dashboard</h3>
        <div class="mt-5 border-t border-gray-200">
            <dl class="divide-y divide-gray-200">
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium text-gray-500">Processing Time</dt>
                    <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="flex-grow">{{ $project->processing_time }} days</span>
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium text-gray-500">Difficulty Level</dt>
                    <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="flex-grow">{{ $project->difficulty_level }}/5</span>
                    </dd>
                </div>
                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5">
                    <dt class="text-sm font-medium text-gray-500">Overtime Probability</dt>
                    <dd class="mt-1 flex text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                        <span class="flex-grow">{{ $overtimeProbability }}%</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection