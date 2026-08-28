<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Per-Exam AI Predictions</h1>
                <p class="text-gray-600 mt-1">Get subject-specific AI analysis for all your upcoming exams.</p>
            </div>
            
            <form action="{{ route('predictions.analyze') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white shadow-md hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Run AI Analysis for Upcoming Exams
                </button>
            </form>
        </div>

        @if(session('error'))
            <div class="rounded-xl bg-red-50 p-4 border border-red-100 shadow-sm animate-pulse">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('status'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($upcomingExams->count() > 0)
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                @foreach($upcomingExams as $exam)
                    @php
                        $prediction = $exam->predictions->first();
                    @endphp
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <!-- Exam Header -->
                        <div class="bg-gradient-to-r from-indigo-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $exam->title }}</h3>
                                <p class="text-sm font-semibold text-indigo-600">{{ $exam->subject?->name ?? 'No Subject' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-1">Exam Date</span>
                                <span class="text-sm font-medium text-gray-900 bg-white px-3 py-1 rounded-full border border-gray-200">{{ \Carbon\Carbon::parse($exam->exam_date)->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <div class="p-6">
                            @if($prediction)
                                <!-- Prediction Results -->
                                <div class="grid grid-cols-2 gap-6 mb-8">
                                    <!-- Score Prediction -->
                                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl p-6 text-white relative overflow-hidden shadow-sm">
                                        <div class="absolute -right-4 -top-4 opacity-10">
                                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"></path></svg>
                                        </div>
                                        <p class="text-indigo-100 font-semibold mb-1 relative z-10">Predicted Score</p>
                                        <div class="flex items-baseline relative z-10">
                                            <span class="text-5xl font-black">{{ number_format($prediction->predicted_exam_score, 1) }}</span>
                                            <span class="text-2xl font-bold ml-1">%</span>
                                        </div>
                                        <p class="text-xs text-indigo-200 mt-2 font-medium relative z-10">Neural Network Engine</p>
                                    </div>

                                    <!-- Risk Analysis -->
                                    <div class="bg-white rounded-2xl p-6 border-2 {{ $prediction->academic_risk === 'High' ? 'border-red-200 bg-red-50' : ($prediction->academic_risk === 'Medium' ? 'border-yellow-200 bg-yellow-50' : 'border-green-200 bg-green-50') }} shadow-sm">
                                        <p class="text-gray-500 font-semibold mb-1">Academic Risk</p>
                                        <h4 class="text-3xl font-black {{ $prediction->academic_risk === 'High' ? 'text-red-600' : ($prediction->academic_risk === 'Medium' ? 'text-yellow-600' : 'text-green-600') }}">{{ $prediction->academic_risk }}</h4>
                                        <p class="text-xs font-semibold text-gray-500 mt-2 mt-4 pt-4 border-t border-gray-200/50">Learner Profile: <br><span class="text-gray-800">{{ $prediction->cluster_label }}</span></p>
                                    </div>
                                </div>

                                <!-- Recommendations -->
                                @if($prediction->recommendations->count() > 0)
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            AI Action Plan
                                        </h4>
                                        <ul class="space-y-3">
                                            @foreach($prediction->recommendations as $rec)
                                                <li class="flex items-start bg-gray-50 p-3 rounded-xl border border-gray-100">
                                                    <div class="flex-shrink-0 mt-0.5">
                                                        @if($rec->priority === 'high')
                                                            <span class="inline-block w-2 h-2 rounded-full bg-red-500 mt-1"></span>
                                                        @elseif($rec->priority === 'medium')
                                                            <span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mt-1"></span>
                                                        @else
                                                            <span class="inline-block w-2 h-2 rounded-full bg-green-500 mt-1"></span>
                                                        @endif
                                                    </div>
                                                    <p class="ml-3 text-sm text-gray-700 leading-relaxed">{{ $rec->recommendation_text }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                <p class="text-xs text-center text-gray-400 font-medium mt-6 pt-4 border-t border-gray-100">Last updated: {{ $prediction->prediction_date }}</p>
                            @else
                                <!-- No Prediction Yet State -->
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 border border-gray-100">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-2">No AI Analysis Yet</h4>
                                    <p class="text-sm text-gray-500 max-w-sm mx-auto">Run the AI Analysis to generate a predicted score and personalized study recommendations for this specific exam.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-600">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">No Upcoming Exams</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-8 text-lg">Add some upcoming exams to your schedule to see what your predicted scores will be!</p>
                <a href="{{ route('exams.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-transform transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Your First Exam
                </a>
            </div>
        @endif

    </div>
</x-app-layout>