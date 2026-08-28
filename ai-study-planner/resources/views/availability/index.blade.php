<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Study Availability</h1>
                <p class="text-gray-600 mt-1">Set your free time to allow AI to schedule your study sessions.</p>
            </div>
            <a href="{{ route('availability.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Availability
            </a>
        </div>

        @if($availabilities->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php 
                    $days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                    
                    // Sort availabilities by day of week
                    $sortedAvailabilities = $availabilities->sortBy('day_of_week');
                @endphp
                
                @foreach($sortedAvailabilities as $avail)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow relative overflow-hidden">
                        <!-- Decorative Header -->
                        <div class="absolute top-0 left-0 right-0 h-2 bg-indigo-500"></div>
                        
                        <div class="flex justify-between items-start mb-4 mt-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $days[$avail->day_of_week] }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase bg-indigo-50 text-indigo-700">
                                {{ $avail->available_hours }} hrs
                            </span>
                        </div>
                        
                        <div class="flex items-center space-x-2 text-gray-600 mb-6 bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="font-medium text-sm">
                                {{ \Carbon\Carbon::parse($avail->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($avail->end_time)->format('g:i A') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <a href="{{ route('availability.edit', $avail) }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Edit</a>
                            <form action="{{ route('availability.destroy', $avail) }}" method="POST" class="inline" @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to remove this availability block?', form: $event.target })">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No availability set</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Define your free time blocks so the AI can automatically schedule your study sessions.</p>
                <a href="{{ route('availability.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Availability
                </a>
            </div>
        @endif
    </div>
</x-app-layout>