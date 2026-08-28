<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Student Profile</h1>
                <p class="text-gray-600 mt-1">Update your academic habits to feed the AI prediction engine.</p>
            </div>
            
            <a href="{{ route('predictions.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-5 h-5 mr-1.5 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Predictions
            </a>
        </div>

        <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden">
            <div class="p-8">
                <div class="max-w-2xl">
                    <section>
                        <header class="mb-8">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                Academic Information
                            </h2>
                            <p class="mt-2 text-sm text-gray-600">Please provide accurate information about your study habits. This data is used by the StudyAI machine learning models to generate predictions and recommendations.</p>
                        </header>

                        <form method="post" action="{{ route('student-profile.update') }}" class="space-y-8">
                            @csrf
                            @method('put')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Study Hours -->
                                <div>
                                    <x-input-label for="study_hours" value="Weekly Study Hours" class="text-sm font-semibold text-gray-700" />
                                    <div class="mt-2 relative rounded-xl shadow-sm">
                                        <x-text-input id="study_hours" name="study_hours" type="number" step="0.1" class="block w-full pr-12 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" :value="old('study_hours', $profile->study_hours)" required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">hrs</span>
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2 text-sm text-red-600" :messages="$errors->get('study_hours')" />
                                </div>

                                <!-- Attendance -->
                                <div>
                                    <x-input-label for="attendance" value="Attendance (%)" class="text-sm font-semibold text-gray-700" />
                                    <div class="mt-2 relative rounded-xl shadow-sm">
                                        <x-text-input id="attendance" name="attendance" type="number" step="0.1" class="block w-full pr-12 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" :value="old('attendance', $profile->attendance)" required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sleep Hours -->
                                <div>
                                    <x-input-label for="sleep_hours" value="Daily Sleep Hours" class="text-sm font-semibold text-gray-700" />
                                    <div class="mt-2 relative rounded-xl shadow-sm">
                                        <x-text-input id="sleep_hours" name="sleep_hours" type="number" step="0.1" class="block w-full pr-12 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" :value="old('sleep_hours', $profile->sleep_hours)" required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">hrs</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Internet Usage -->
                                <div>
                                    <x-input-label for="internet_usage" value="Daily Internet Usage" class="text-sm font-semibold text-gray-700" />
                                    <div class="mt-2 relative rounded-xl shadow-sm">
                                        <x-text-input id="internet_usage" name="internet_usage" type="number" step="0.1" class="block w-full pr-12 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" :value="old('internet_usage', $profile->internet_usage)" required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">hrs</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assignments Completed -->
                                <div>
                                    <x-input-label for="assignments_completed" value="Total Assignments Completed" class="text-sm font-semibold text-gray-700" />
                                    <x-text-input id="assignments_completed" name="assignments_completed" type="number" class="mt-2 block w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :value="old('assignments_completed', $profile->assignments_completed)" required />
                                </div>

                                <!-- Previous Score -->
                                <div>
                                    <x-input-label for="previous_score" value="Previous Exam Score (Optional)" class="text-sm font-semibold text-gray-700" />
                                    <div class="mt-2 relative rounded-xl shadow-sm">
                                        <x-text-input id="previous_score" name="previous_score" type="number" step="0.1" class="block w-full pr-12 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" :value="old('previous_score', $profile->previous_score)" />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                                <button type="submit" class="px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    {{ __('Save Profile') }}
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>