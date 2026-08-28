<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Subjects</h1>
                <p class="text-gray-600 mt-1">Manage the subjects you are currently studying.</p>
            </div>
            <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Subject
            </a>
        </div>

        @if($subjects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subjects as $subject)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow relative group">
                        <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-50 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold text-gray-900 mb-1 pr-8 truncate" title="{{ $subject->name }}">{{ $subject->name }}</h3>
                            <p class="text-sm font-semibold text-indigo-600 bg-indigo-50 inline-block px-2 py-0.5 rounded-md mb-6">{{ $subject->code ?? 'N/A' }}</p>
                            
                            <div class="flex items-center space-x-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('subjects.edit', $subject) }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Edit</a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="inline" @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this subject?', form: $event.target })">
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
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No subjects yet</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Add your first subject to start building your personalized study plan.</p>
                <a href="{{ route('subjects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Subject
                </a>
            </div>
        @endif
    </div>
</x-app-layout>