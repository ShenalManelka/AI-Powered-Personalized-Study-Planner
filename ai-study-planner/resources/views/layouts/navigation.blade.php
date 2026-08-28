<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center flex-1">
                <!-- Logo -->
                <div class="shrink-0 flex items-center mr-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('favicon.jpg') }}" alt="StudyAI Logo" style="height: 40px; width: auto;" class="object-contain rounded">
                        <span class="text-xl font-bold text-gray-900 tracking-tight hidden sm:block">Study<span class="text-indigo-600">AI</span></span>
                    </a>
                </div>

                <!-- Navigation Links (Left) -->
                <div class="hidden space-x-6 sm:-my-px sm:flex h-full">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-sm font-medium">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')" class="text-sm font-medium">
                        {{ __('Subjects') }}
                    </x-nav-link>
                    <x-nav-link :href="route('assignments.index')" :active="request()->routeIs('assignments.*')" class="text-sm font-medium">
                        {{ __('Assignments') }}
                    </x-nav-link>
                    <x-nav-link :href="route('exams.index')" :active="request()->routeIs('exams.*')" class="text-sm font-medium">
                        {{ __('Exams') }}
                    </x-nav-link>
                    <x-nav-link :href="route('availability.index')" :active="request()->routeIs('availability.*')" class="text-sm font-medium">
                        {{ __('Availability') }}
                    </x-nav-link>
                    <x-nav-link :href="route('study-plans.index')" :active="request()->routeIs('study-plans.*')" class="text-sm font-medium">
                        {{ __('Study Plan') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden sm:flex sm:items-center space-x-6 h-full">
                <x-nav-link :href="route('predictions.index')" :active="request()->routeIs('predictions.*')" class="text-sm font-medium text-indigo-600 border-indigo-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    {{ __('AI Predictions') }}
                </x-nav-link>
                <x-nav-link :href="route('recommendations.index')" :active="request()->routeIs('recommendations.*')" class="text-sm font-medium text-indigo-600 border-indigo-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    {{ __('Recommendations') }}
                </x-nav-link>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none transition ease-in-out duration-150">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-2 font-bold text-xs">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-sm text-gray-700 hover:bg-gray-50">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-sm text-red-600 hover:bg-red-50">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none focus:bg-indigo-50 focus:text-indigo-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100 shadow-xl absolute w-full z-50">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-lg">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('subjects.index')" :active="request()->routeIs('subjects.*')" class="rounded-lg">
                {{ __('Subjects') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('assignments.index')" :active="request()->routeIs('assignments.*')" class="rounded-lg">
                {{ __('Assignments') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('exams.index')" :active="request()->routeIs('exams.*')" class="rounded-lg">
                {{ __('Exams') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('availability.index')" :active="request()->routeIs('availability.*')" class="rounded-lg">
                {{ __('Availability') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('study-plans.index')" :active="request()->routeIs('study-plans.*')" class="rounded-lg">
                {{ __('Study Plan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('predictions.index')" :active="request()->routeIs('predictions.*')" class="text-indigo-600 rounded-lg">
                {{ __('AI Predictions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('recommendations.index')" :active="request()->routeIs('recommendations.*')" class="text-indigo-600 rounded-lg">
                {{ __('Recommendations') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 flex items-center">
                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mr-3 font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-4 mb-4">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-lg">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-red-600 rounded-lg hover:bg-red-50">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
