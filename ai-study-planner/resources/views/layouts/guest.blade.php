<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StudyAi') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col sm:flex-row">
        <!-- Left Side: Branding & Info -->
        <div class="hidden sm:flex sm:w-1/2 lg:w-5/12 bg-indigo-600 text-white p-12 flex-col justify-between relative overflow-hidden">
            <!-- Decorative bg -->
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_bottom_left,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
            
            <div class="relative z-10">
                <a href="/" class="flex items-center space-x-2 mb-16">
                    <img src="{{ asset('favicon.jpg') }}" alt="StudyAI Logo" style="height: 40px; width: auto;" class="object-contain rounded-lg">
                    <span class="text-2xl font-bold tracking-tight">StudyAI</span>
                </a>
                
                <h1 class="text-4xl font-bold mb-6 leading-tight">Your intelligent academic planning assistant.</h1>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-indigo-50 font-medium">AI-powered performance predictions</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-indigo-50 font-medium">Personalized study plans</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-indigo-500 rounded-full flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-indigo-50 font-medium">Intelligent recommendations</span>
                    </div>
                </div>
            </div>
            
            <div class="relative z-10 text-indigo-200 text-sm">
                &copy; {{ date('Y') }} StudyAI. Built for students.
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12">
            <!-- Mobile Header -->
            <div class="sm:hidden mb-8 flex items-center justify-center space-x-2">
                <img src="{{ asset('favicon.jpg') }}" alt="StudyAI Logo" style="height: 40px; width: auto;" class="object-contain rounded">
                <span class="text-2xl font-bold tracking-tight text-gray-900">Study<span class="text-indigo-600">AI</span></span>
            </div>
            
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
