<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Academic Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update your academic information so the AI system can provide personalized predictions, recommendations, and study planning.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.academic.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="study_hours" :value="__('Study Hours per Day')" />
                <x-text-input id="study_hours" name="study_hours" type="number" step="0.1" class="mt-1 block w-full" :value="old('study_hours', $academicProfile->study_hours ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('study_hours')" />
            </div>

            <div>
                <x-input-label for="attendance" :value="__('Attendance (%)')" />
                <x-text-input id="attendance" name="attendance" type="number" step="0.1" class="mt-1 block w-full" :value="old('attendance', $academicProfile->attendance ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('attendance')" />
            </div>

            <div>
                <x-input-label for="sleep_hours" :value="__('Sleep Hours per Day')" />
                <x-text-input id="sleep_hours" name="sleep_hours" type="number" step="0.1" class="mt-1 block w-full" :value="old('sleep_hours', $academicProfile->sleep_hours ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('sleep_hours')" />
            </div>

            <div>
                <x-input-label for="internet_usage" :value="__('Internet Usage per Day (hours)')" />
                <x-text-input id="internet_usage" name="internet_usage" type="number" step="0.1" class="mt-1 block w-full" :value="old('internet_usage', $academicProfile->internet_usage ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('internet_usage')" />
            </div>



        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                {{ __('SAVE ACADEMIC INFORMATION') }}
            </button>

            @if (session('status') === 'academic-profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-bold"
                >{{ __('Academic information updated successfully.') }}</p>
            @endif
        </div>
    </form>
</section>
