<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Assignments</h1>
                <p class="text-gray-600 mt-1">Track your academic tasks and deadlines.</p>
            </div>
            <a href="{{ route('assignments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Assignment
            </a>
        </div>

        @if($assignments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($assignments as $assignment)
                    @php
                        $isOverdue = \Carbon\Carbon::parse($assignment->deadline)->isPast() && strtolower($assignment->status) != 'completed';
                        
                        $statusColor = match(strtolower($assignment->status)) {
                            'completed' => 'bg-green-100 text-green-700',
                            'pending' => $isOverdue ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                        
                        $statusText = $isOverdue ? 'Overdue' : ucfirst($assignment->status);
                    @endphp
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                            @if(isset($assignment->priority) && strtolower($assignment->priority) == 'high')
                                <span class="flex items-center text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    High Priority
                                </span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-1 line-clamp-2" title="{{ $assignment->title }}">{{ $assignment->title }}</h3>
                        <p class="text-sm font-semibold text-indigo-600 mb-4">{{ $assignment->subject?->name ?? 'No Subject' }}</p>
                        
                        <div class="mt-auto">
                            <div class="flex items-center text-sm text-gray-500 mb-6">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Due {{ \Carbon\Carbon::parse($assignment->deadline)->format('M d, Y') }}
                            </div>
                            
                            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('assignments.edit', $assignment) }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Edit</a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('assignments.destroy', $assignment) }}" method="POST" class="inline" @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this assignment?', form: $event.target })">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No assignments yet</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Add your first assignment to start building your personalized study plan.</p>
                <a href="{{ route('assignments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Assignment
                </a>
            </div>
        @endif
    </div>
</x-app-layout>