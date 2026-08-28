<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create your StudyAI account</h2>
        <p class="text-gray-600">Register to start building personalized academic plans.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-sm font-semibold text-gray-700" />
            <x-text-input id="name" class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-semibold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-semibold text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                {{ __('Create Account') }}
            </button>
        </div>
    </form>

    <div class="mt-8 text-center text-sm text-gray-600">
        Already have an account? 
        <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Log In</a>
    </div>
</x-guest-layout>
