<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex items-center justify-center px-4">
<div class="max-w-5xl w-full grid md:grid-cols-2 items-center gap-12">
    <div class="space-y-6 text-center md:text-left">
        <h1 class="text-4xl font-bold">Your Smart Task Manager</h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
            Organize your workflow effortlessly. Assign tasks, track progress, manage priorities, and stay productive — all in one place.
        </p>
        <ul class="text-sm text-gray-500 dark:text-gray-400 list-disc pl-5 space-y-1">
            <li>🧠 Weekly overview to visualize your workload</li>
            <li>👥 Assign up to 4 teammates per task</li>
            <li>⏱ Smart scheduling with time limits per user</li>
            <li>🔥 Clean UI built with Laravel + Tailwind</li>
        </ul>

        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4 pt-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 transition">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2 rounded border border-indigo-600 text-indigo-600 hover:bg-indigo-100 dark:hover:bg-indigo-800 dark:hover:text-white transition">
                        Log In
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-2 rounded border border-gray-400 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>

        <div class="pt-6 text-xs text-gray-400 dark:text-gray-500">
            Built with Laravel • Designed for everyday productivity
        </div>
    </div>
</div>
</body>
</html>
