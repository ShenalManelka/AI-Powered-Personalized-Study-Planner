<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Actionable Recommendations</h1>
                <p class="text-gray-600 mt-1">AI-driven tasks to improve your academic performance.</p>
            </div>
            
            <a href="{{ route('predictions.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-5 h-5 mr-1.5 -ml-1 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                Run AI Analysis
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Your Action Plan</h3>
            </div>
            
            <div class="p-6">
                @if($recommendations->count() > 0)
                    @php
                        $pending = $recommendations->where('is_completed', false);
                        $completed = $recommendations->where('is_completed', true);
                    @endphp
                    
                    @if($pending->count() > 0)
                        <div class="mb-8">
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">To Do</h4>
                            <div class="space-y-3">
                                @foreach($pending as $rec)
                                    <div class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 bg-white hover:shadow-md transition-shadow group">
                                        <form action="{{ route('recommendations.complete', $rec) }}" method="POST" class="shrink-0 mt-0.5">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-6 h-6 rounded-full border-2 border-gray-300 flex items-center justify-center text-transparent hover:text-green-500 hover:border-green-500 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" title="Mark Complete">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </form>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-center mb-1 space-x-2">
                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md 
                                                    {{ $rec->priority == 'high' ? 'bg-red-100 text-red-800' : '' }}
                                                    {{ $rec->priority == 'medium' ? 'bg-amber-100 text-amber-800' : '' }}
                                                    {{ $rec->priority == 'low' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                    {{ $rec->priority }} Priority
                                                </span>
                                                <span class="text-xs font-medium text-gray-400">&bull; {{ $rec->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-800 font-medium leading-relaxed">
                                                {{ $rec->recommendation_text }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($completed->count() > 0)
                        <div>
                            <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                                Completed
                                <span class="ml-2 bg-green-100 text-green-700 py-0.5 px-2 rounded-full text-xs">{{ $completed->count() }}</span>
                            </h4>
                            <div class="space-y-3 opacity-70">
                                @foreach($completed as $rec)
                                    <div class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 bg-gray-50">
                                        <form action="{{ route('recommendations.complete', $rec) }}" method="POST" class="shrink-0 mt-0.5">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="w-6 h-6 rounded-full border-2 border-green-500 bg-green-500 flex items-center justify-center text-white hover:bg-gray-200 hover:border-gray-300 hover:text-gray-400 transition-colors focus:outline-none" title="Undo">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            </button>
                                        </form>
                                        
                                        <div class="flex-1">
                                            <div class="flex items-center mb-1 space-x-2">
                                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded-md bg-gray-200 text-gray-600">
                                                    {{ $rec->priority }} Priority
                                                </span>
                                            </div>
                                            <p class="text-gray-500 font-medium leading-relaxed line-through">
                                                {{ $rec->recommendation_text }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">You're all caught up!</h3>
                        <p class="text-gray-500 max-w-sm mx-auto mb-6">There are no pending recommendations at the moment. Run an AI analysis to get new insights.</p>
                        <a href="{{ route('predictions.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                            Run AI Analysis
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>