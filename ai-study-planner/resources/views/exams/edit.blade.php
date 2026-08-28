<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Exam</h1>
            <p class="text-gray-600 mt-1">Update the details of your upcoming exam.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('exams.update', $exam) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <x-input-label for="title" value="Exam Title" class="text-sm font-semibold text-gray-700" />
                    <x-text-input id="title" name="title" value="{{ $exam->title }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                
                <div>
                    <x-input-label for="subject_id" value="Subject" class="text-sm font-semibold text-gray-700" />
                    <select id="subject_id" name="subject_id" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $exam->subject_id == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="exam_date" value="Exam Date" class="text-sm font-semibold text-gray-700" />
                        <x-text-input id="exam_date" type="date" name="exam_date" value="{{ \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d') }}" required class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    </div>
                    
                    <div>
                        <x-input-label for="exam_type" value="Exam Type" class="text-sm font-semibold text-gray-700" />
                        <select id="exam_type" name="exam_type" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="midterm" {{ $exam->exam_type == 'midterm' ? 'selected' : '' }}>Midterm</option>
                            <option value="final" {{ $exam->exam_type == 'final' ? 'selected' : '' }}>Final Exam</option>
                            <option value="quiz" {{ $exam->exam_type == 'quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="practical" {{ $exam->exam_type == 'practical' ? 'selected' : '' }}>Practical</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <x-input-label for="status" value="Status" class="text-sm font-semibold text-gray-700" />
                        <select id="status" name="status" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="upcoming" {{ $exam->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="completed" {{ $exam->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="score" value="Score (%)" class="text-sm font-semibold text-gray-700" />
                        <x-text-input id="score" type="number" step="0.1" name="score" value="{{ $exam->score }}" class="block w-full mt-2 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional" />
                    </div>
                </div>
                <div>
                    <x-input-label for="topics" value="Topics Covered (Optional)" class="text-sm font-semibold text-gray-700" />
                    <textarea id="topics" name="topics" rows="3" class="block w-full mt-2 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $exam->topics }}</textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('exams.index') }}" class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Update Exam
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>