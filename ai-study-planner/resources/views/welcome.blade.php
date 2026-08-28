<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudyAI - Personalized Academic Assistant</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 top-0 left-0 border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('favicon.jpg') }}" alt="StudyAI Logo" style="height: 40px; width: auto;" class="object-contain rounded">
                    <span class="text-2xl font-bold text-gray-900 tracking-tight">Study<span class="text-indigo-600">AI</span></span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Home</a>
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">Features</a>
                    <a href="#how-it-works" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">How It Works</a>
                    <a href="#about" class="text-gray-600 hover:text-indigo-600 font-medium transition-colors">About</a>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 font-medium hidden md:block">Log In</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="max-w-2xl">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                        Study Smarter. <br/> Plan Better. <br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Achieve More.</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 mb-8 leading-relaxed">
                        StudyAI uses intelligent academic analysis to help undergraduate students understand their performance, identify academic risks, and create personalized study plans.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                            Get Started
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex justify-center items-center bg-white text-gray-700 border border-gray-200 px-8 py-3.5 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                            Log In
                        </a>
                    </div>
                </div>
                <div class="relative lg:ml-10 hidden md:block">
                    <!-- Dashboard Visual Mockup -->
                    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-amber-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Dashboard Overview</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-indigo-50 rounded-xl p-4">
                                <p class="text-indigo-600 text-sm font-medium mb-1">Predicted Score</p>
                                <p class="text-3xl font-bold text-gray-900">78.5%</p>
                            </div>
                            <div class="bg-green-50 rounded-xl p-4">
                                <p class="text-green-600 text-sm font-medium mb-1">Academic Risk</p>
                                <p class="text-3xl font-bold text-gray-900">LOW</p>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-gray-500 text-sm font-medium mb-3">Today's Study Plan</p>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center bg-white p-3 rounded-lg shadow-sm">
                                    <span class="text-sm font-medium text-gray-700">Machine Learning</span>
                                    <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded">2 hrs</span>
                                </div>
                                <div class="flex justify-between items-center bg-white p-3 rounded-lg shadow-sm">
                                    <span class="text-sm font-medium text-gray-700">Data Structures</span>
                                    <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded">1 hr</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Intelligent Features</h2>
                <p class="text-lg text-gray-600">Everything you need to stay on top of your coursework and optimize your academic performance.</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:border-indigo-100 transition-all">
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-6 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">AI Performance Prediction</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Predict expected academic performance using student learning data and past academic history.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:border-indigo-100 transition-all">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-6 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Academic Risk Detection</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Identify whether a student may be at Low, Medium, or High academic risk to intervene early.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:border-indigo-100 transition-all">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-6 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Personalized Study Planning</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Generate realistic study schedules based on your exams, assignments, and available free time.</p>
                </div>
                <!-- Feature 4 -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-xl hover:border-indigo-100 transition-all">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-6 text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Intelligent Recommendations</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">Provide personalized suggestions and insights based on student performance and academic behavior.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-lg text-gray-600">A simple workflow to transform your academic journey.</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8 relative">
                <!-- Connecting Line (Desktop only) -->
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-indigo-100 -z-10 transform -translate-y-1/2"></div>
                
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-xl font-bold text-indigo-600 mx-auto mb-6 shadow-sm">1</div>
                    <h3 class="font-bold text-gray-900 mb-2">Enter Information</h3>
                    <p class="text-sm text-gray-600">Add your subjects, assignments, exams, and study availability.</p>
                </div>
                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-xl font-bold text-indigo-600 mx-auto mb-6 shadow-sm">2</div>
                    <h3 class="font-bold text-gray-900 mb-2">AI Analyses Performance</h3>
                    <p class="text-sm text-gray-600">Our machine learning models predict your score and risk level.</p>
                </div>
                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-xl font-bold text-indigo-600 mx-auto mb-6 shadow-sm">3</div>
                    <h3 class="font-bold text-gray-900 mb-2">Get Recommendations</h3>
                    <p class="text-sm text-gray-600">Receive personalized academic suggestions tailored to your profile.</p>
                </div>
                <!-- Step 4 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-white border-4 border-indigo-100 rounded-full flex items-center justify-center text-xl font-bold text-indigo-600 mx-auto mb-6 shadow-sm">4</div>
                    <h3 class="font-bold text-gray-900 mb-2">Follow Your Plan</h3>
                    <p class="text-sm text-gray-600">Automatically generate and follow a stress-free daily study schedule.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call To Action -->
    <section class="py-20 bg-indigo-600 relative overflow-hidden">
        <!-- Decorative bg -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl font-bold text-white mb-6">Ready to take control of your studies?</h2>
            <p class="text-indigo-100 text-lg mb-10">Join StudyAI today and experience the power of intelligent academic planning.</p>
            <a href="{{ route('register') }}" class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-xl font-bold text-lg hover:bg-indigo-50 transition-colors shadow-lg">
                Create Your Study Plan
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="about" class="bg-gray-900 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex items-center justify-center space-x-2 mb-6">
                <img src="{{ asset('favicon.jpg') }}" alt="StudyAI Logo" style="height: 32px; width: auto;" class="object-contain rounded opacity-80">
                <span class="text-xl font-bold text-white tracking-tight">Study<span class="text-indigo-500">AI</span></span>
            </div>
            <p class="text-gray-400 mb-6 text-sm">AI-Powered Personalized Study Planner</p>
            <div class="flex justify-center space-x-6 mb-8 text-sm">
                <a href="#features" class="text-gray-400 hover:text-white transition-colors">Features</a>
                <a href="#how-it-works" class="text-gray-400 hover:text-white transition-colors">How It Works</a>
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Login</a>
                <a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Register</a>
            </div>
            <p class="text-gray-600 text-xs">
                &copy; {{ date('Y') }} StudyAI. All rights reserved. Built for students.
            </p>
        </div>
    </footer>

</body>
</html>
