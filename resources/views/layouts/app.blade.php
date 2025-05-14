<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Overtime - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex items-center flex-shrink-0">
                            <img src="{{ asset('images/pilar-kreatif.jpeg') }}" alt="SPK Overtime Logo" class="h-8 w-auto">                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900">
                                Dashboard
                            </a>
                            @if(auth()->user()->role === 'manager')
                                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">
                                    Projects
                                </a>
                            @endif
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('projects.index') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">
                                    Projects
                                </a>
                                <a href="{{ route('projects.fuzzy-analysis') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">
                                    Fuzzy Analysis
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="relative ml-3">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex text-sm transition duration-150 ease-in-out border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300">
                                        <span class="px-4 py-2">{{ Auth::user()->name }}</span>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
