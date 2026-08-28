<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Availability</h1>
            <p class="text-gray-600 mt-1">Update when you are free to study.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('availability.update', $availability) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <x-input-label for="day_of_week" value="Day of the Week" class="text-sm font-semibold text-gray-700" />
                    <select id="day_of_week" name="day_of_week" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="1" {{ $availability->day_of_week == 1 ? 'selected' : '' }}>Monday</option>
                        <option value="2" {{ $availability->day_of_week == 2 ? 'selected' : '' }}>Tuesday</option>
                        <option value="3" {{ $availability->day_of_week == 3 ? 'selected' : '' }}>Wednesday</option>
                        <option value="4" {{ $availability->day_of_week == 4 ? 'selected' : '' }}>Thursday</option>
                        <option value="5" {{ $availability->day_of_week == 5 ? 'selected' : '' }}>Friday</option>
                        <option value="6" {{ $availability->day_of_week == 6 ? 'selected' : '' }}>Saturday</option>
                        <option value="0" {{ $availability->day_of_week == 0 ? 'selected' : '' }}>Sunday</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="start_time" value="Start Time" class="text-sm font-semibold text-gray-700" />
                        <x-text-input id="start_time" type="time" name="start_time" value="{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    
                    <div>
                        <x-input-label for="end_time" value="End Time" class="text-sm font-semibold text-gray-700" />
                        <x-text-input id="end_time" type="time" name="end_time" value="{{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('availability.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Update Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>