<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Upcoming Exams</h1>
                <p class="text-gray-600 mt-1">Track your examination dates and countdowns.</p>
            </div>
            <a href="{{ route('exams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add Exam
            </a>
        </div>

        @php
            $upcomingExams = $exams->where('status', 'upcoming');
            $completedExams = $exams->where('status', 'completed');
        @endphp

        @if($upcomingExams->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($upcomingExams as $exam)
                    @php
                        $examDate = \Carbon\Carbon::parse($exam->exam_date);
                        $daysRemaining = now()->startOfDay()->diffInDays($examDate->startOfDay(), false);
                        
                        $isPast = $daysRemaining < 0;
                        $isUrgent = $daysRemaining >= 0 && $daysRemaining <= 7;
                        
                        $cardStyle = $isPast ? 'bg-gray-50 border-gray-200 opacity-70' : ($isUrgent ? 'bg-red-50 border-red-100' : 'bg-white border-gray-100');
                        $textColor = $isPast ? 'text-gray-500' : ($isUrgent ? 'text-red-700' : 'text-indigo-600');
                    @endphp
                    <div class="rounded-2xl shadow-sm border p-6 hover:shadow-md transition-shadow flex flex-col {{ $cardStyle }}">
                        
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase bg-white/60 text-gray-700 border border-gray-200 shadow-sm">
                                {{ ucfirst($exam->exam_type) }}
                            </span>
                            @if($isPast)
                                <span class="text-xs font-bold text-gray-500">Past Exam</span>
                            @elseif($daysRemaining == 0)
                                <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded animate-pulse">TODAY</span>
                            @else
                                <span class="text-xs font-bold {{ $textColor }}">{{ $daysRemaining }} days remaining</span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-1 line-clamp-2" title="{{ $exam->title }}">{{ $exam->title }}</h3>
                        <p class="text-sm font-semibold text-gray-600 mb-4">{{ $exam->subject?->name ?? 'No Subject' }}</p>
                        
                        <div class="mt-auto">
                            <div class="flex items-center text-sm font-medium text-gray-800 mb-6 bg-white/60 p-3 rounded-xl border border-gray-100">
                                <svg class="w-5 h-5 mr-2 {{ $textColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $examDate->format('l, F j, Y') }}
                            </div>
                            
                            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('exams.edit', $exam) }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Edit</a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="inline" @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this exam?', form: $event.target })">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($completedExams->count() > 0)
            <div class="mb-8 mt-12">
                <h1 class="text-3xl font-bold text-gray-900">Completed Exams</h1>
                <p class="text-gray-600 mt-1">Review your past performance and marks.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($completedExams as $exam)
                    @php
                        $examDate = \Carbon\Carbon::parse($exam->exam_date);
                    @endphp
                    <div class="rounded-2xl shadow-sm border p-6 hover:shadow-md transition-shadow flex flex-col bg-gray-50 border-gray-200">
                        
                        <div class="flex justify-between items-start mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase bg-white/60 text-gray-700 border border-gray-200 shadow-sm">
                                {{ ucfirst($exam->exam_type) }}
                            </span>
                            @if($exam->score !== null)
                                <span class="text-sm font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">Score: {{ $exam->score }}%</span>
                            @else
                                <span class="text-sm font-bold text-gray-500 bg-gray-200 px-2 py-0.5 rounded">No Score</span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-1 line-clamp-2" title="{{ $exam->title }}">{{ $exam->title }}</h3>
                        <p class="text-sm font-semibold text-gray-600 mb-4">{{ $exam->subject?->name ?? 'No Subject' }}</p>
                        
                        <div class="mt-auto">
                            <div class="flex items-center text-sm font-medium text-gray-800 mb-6 bg-white/60 p-3 rounded-xl border border-gray-200">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ $examDate->format('l, F j, Y') }}
                            </div>
                            
                            <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                                <a href="{{ route('exams.edit', $exam) }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">Edit</a>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="inline" @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this exam?', form: $event.target })">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        
        @if($exams->count() == 0)
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No exams yet</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Add your upcoming exams to let StudyAI schedule your revision time.</p>
                <a href="{{ route('exams.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5 mr-1 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Exam
                </a>
            </div>
        @endif
    </div>
</x-app-layout>