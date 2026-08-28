<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Assignment</h1>
            <p class="text-gray-600 mt-1">Update the details of your academic task.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('assignments.update', $assignment) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <x-input-label for="title" value="Assignment Title" class="text-sm font-semibold text-gray-700" />
                    <x-text-input id="title" name="title" value="{{ $assignment->title }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                
                <div>
                    <x-input-label for="subject_id" value="Subject" class="text-sm font-semibold text-gray-700" />
                    <select id="subject_id" name="subject_id" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="deadline" value="Deadline" class="text-sm font-semibold text-gray-700" />
                        <x-text-input id="deadline" type="datetime-local" name="deadline" value="{{ \Carbon\Carbon::parse($assignment->deadline)->format('Y-m-d\TH:i') }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    
                    <div>
                        <x-input-label for="status" value="Status" class="text-sm font-semibold text-gray-700" />
                        <select id="status" name="status" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="pending" {{ $assignment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $assignment->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $assignment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <div>
                    <x-input-label for="priority" value="Priority" class="text-sm font-semibold text-gray-700" />
                    <select id="priority" name="priority" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="low" {{ $assignment->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $assignment->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $assignment->priority == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('assignments.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Update Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>