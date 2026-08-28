<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add Availability</h1>
            <p class="text-gray-600 mt-1">Define when you are free to study so AI can build your plan.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('availability.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <x-input-label for="day_of_week" value="Day of the Week" class="text-sm font-semibold text-gray-700" />
                    <select id="day_of_week" name="day_of_week" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                        <option value="0">Sunday</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="start_time" value="Start Time" class="text-sm font-semibold text-gray-700" />
                        <x-text-input id="start_time" type="time" name="start_time" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    
                    <div>
                        <x-input-label for="end_time" value="End Time" class="text-sm font-semibold text-gray-700" />
                        <x-text-input id="end_time" type="time" name="end_time" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                </div>

                <!-- Hidden field for preferred hours (optional calculation handling if implemented in backend) -->
                
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('availability.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Save Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>