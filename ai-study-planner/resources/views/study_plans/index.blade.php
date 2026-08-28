<x-app-layout>
    <div class="py-12" x-data="{ showAddModal: false, showEditModal: false, editItem: {}, viewMode: 'week', selectedDayFilter: '{{ $selectedDate }}' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Study Plan</h1>
                    <p class="text-gray-600 mt-1">Your AI-generated daily academic schedule.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
                    <button @click="showAddModal = true" class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-full font-bold text-sm text-gray-700 shadow-sm hover:shadow hover:bg-gray-50 focus:outline-none transition-all group">
                        <div class="w-6 h-6 mr-2 rounded-full bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        Add Session
                    </button>
                    
                    <form action="{{ route('study-plans.generate') }}" method="POST" @submit.prevent="$dispatch('open-confirm', { message: 'Regenerate a new personalized study plan? This will archive your current active plan.', form: $event.target })">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gray-900 border border-transparent rounded-full font-bold text-sm text-white shadow-sm hover:shadow-md hover:bg-black focus:outline-none transition-all group">
                            <svg class="w-4 h-4 mr-2 text-indigo-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Regenerate Plan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Error Handling / Messages (Handled by global layout usually, but kept for specific plan errors) -->
            @if (isset($autoGenerateError) && $autoGenerateError)
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex justify-between items-center shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-amber-600 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span class="text-sm font-medium text-amber-800">{{ $autoGenerateError }}</span>
                    </div>
                    <a href="{{ route('availability.index') }}" class="text-sm font-bold text-amber-800 hover:text-amber-900 underline">Update Availability</a>
                </div>
            @endif

            @if (isset($noTasksMessage) && $noTasksMessage)
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 flex items-center shadow-sm">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="text-sm font-medium text-blue-800">{{ $noTasksMessage }}</span>
                </div>
            @endif

            <!-- Overall Progress Card -->
            <div class="bg-gray-900 rounded-[2rem] shadow-xl p-8 flex flex-col md:flex-row md:items-center justify-between gap-8 relative overflow-hidden group">
                <!-- Background decoration -->
                <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none transition-transform duration-700 group-hover:scale-125"></div>
                <div class="absolute left-0 bottom-0 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl -ml-10 -mb-10 pointer-events-none"></div>

                <div class="relative z-10 md:w-5/12">
                    <div class="inline-flex items-center px-3 py-1 bg-white/10 rounded-full border border-white/20 mb-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></div>
                        <span class="text-[10px] font-bold text-white tracking-widest uppercase">Active Plan</span>
                    </div>
                    <h3 class="text-3xl font-black text-white tracking-tight">Your Progress</h3>
                    @if($plan)
                        <p class="text-sm text-gray-400 mt-2 font-medium leading-relaxed">You've completed <span class="text-white font-bold">{{ $completedSessions }}</span> out of {{ $totalSessions }} study sessions. Keep the momentum going!</p>
                    @else
                        <p class="text-sm text-gray-400 mt-2 font-medium">No active plan currently. Click regenerate to create one.</p>
                    @endif
                </div>
                
                @if($plan)
                <div class="relative z-10 md:w-7/12 bg-white/5 rounded-2xl p-6 border border-white/10 backdrop-blur-md shadow-inner">
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Completion Rate</span>
                            <span class="text-4xl font-black text-white leading-none">{{ $progress }}%</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1">Sessions Left</span>
                            <span class="text-2xl font-bold text-gray-200 leading-none">{{ $totalSessions - $completedSessions }}</span>
                        </div>
                    </div>
                    <div class="w-full bg-gray-800/80 rounded-full h-4 overflow-hidden shadow-inner border border-white/5">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full rounded-full transition-all duration-1000 ease-out relative overflow-hidden" style="width: {{ $progress }}%">
                            <div class="absolute inset-0 bg-white/20 w-full h-full transform -skew-x-12 translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            @if($plan)
            <!-- Weekly Schedule View -->
            <div class="bg-gradient-to-br from-indigo-50/50 via-white to-blue-50/50 rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative">
                <!-- Date Navigation Bar -->
                <div class="bg-white/60 backdrop-blur-md border-b border-gray-100 p-4 sm:px-8 sm:py-6 flex flex-wrap sm:flex-nowrap items-center justify-between z-20 relative gap-3 sm:gap-0">
                    <div class="flex items-center space-x-2 order-2 sm:order-1">
                        <a href="{{ route('study-plans.index', ['date' => $startOfWeek->copy()->subWeek()->format('Y-m-d')]) }}" class="p-2 sm:p-2.5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all shadow-sm group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <a href="{{ route('study-plans.index', ['date' => $startOfWeek->copy()->addWeek()->format('Y-m-d')]) }}" class="p-2 sm:p-2.5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all shadow-sm group">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                    
                    <div class="text-center w-full sm:w-auto sm:absolute sm:left-1/2 sm:transform sm:-translate-x-1/2 order-1 sm:order-2">
                        <h2 class="text-base sm:text-2xl font-black text-gray-900 tracking-tight flex items-center justify-center">
                            {{ $startOfWeek->format('M j') }} - {{ $endOfWeek->format('M j, Y') }}
                            @if($startOfWeek->isSameMonth(now()) && $startOfWeek->isSameYear(now()) && now()->between($startOfWeek, $endOfWeek))
                                <span class="ml-2 sm:ml-3 px-2 sm:px-3 py-0.5 sm:py-1 bg-indigo-600 text-white text-[9px] sm:text-[10px] font-black uppercase rounded-full shadow-sm tracking-wider inline-block">This Week</span>
                            @endif
                        </h2>
                    </div>
                    
                    <!-- Filter Toggle -->
                    <div class="flex items-center bg-gray-100 p-1 rounded-xl shadow-inner border border-gray-200 order-3 ml-auto sm:ml-0">
                        <button @click="viewMode = 'week'" :class="viewMode === 'week' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-3 sm:px-4 py-1 sm:py-1.5 text-xs sm:text-sm font-bold rounded-lg transition-all">Week</button>
                        <button @click="viewMode = 'day'" :class="viewMode === 'day' ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="px-3 sm:px-4 py-1 sm:py-1.5 text-xs sm:text-sm font-bold rounded-lg transition-all">Day</button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="p-4 sm:p-6 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
                    <div class="min-w-[800px]">
                        <!-- Days Header -->
                        <div class="grid grid-cols-[60px_repeat(7,1fr)] gap-3 mb-4 transition-all">
                            <div></div>
                            @foreach($weekDays as $day)
                                <div class="text-center cursor-pointer group" @click="viewMode = 'day'; selectedDayFilter = '{{ $day->format('Y-m-d') }}'">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 group-hover:text-indigo-500 transition-colors">{{ $day->format('D') }}</p>
                                    @if($day->isToday())
                                        <div :class="selectedDayFilter === '{{ $day->format('Y-m-d') }}' && viewMode === 'day' ? 'bg-indigo-700 text-white shadow-lg ring-4 ring-indigo-100 scale-110' : 'bg-indigo-600 text-white shadow-md ring-4 ring-indigo-50'" class="inline-flex items-center justify-center w-10 h-10 rounded-full font-black text-lg transition-all">
                                            {{ $day->format('j') }}
                                        </div>
                                    @else
                                        <div :class="selectedDayFilter === '{{ $day->format('Y-m-d') }}' && viewMode === 'day' ? 'bg-indigo-600 text-white shadow-lg ring-4 ring-indigo-100 scale-110' : 'text-gray-700 hover:bg-gray-100'" class="inline-flex items-center justify-center w-10 h-10 rounded-full font-bold text-lg transition-all">
                                            {{ $day->format('j') }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Time Grid -->
                        <div :class="viewMode === 'week' ? 'grid-cols-[60px_repeat(7,1fr)]' : 'grid-cols-[60px_1fr]'" class="grid gap-3 relative transition-all">
                            <!-- Time Axis -->
                            <div class="col-span-1 relative h-[960px]">
                                @for($i = 8; $i <= 24; $i++)
                                    <div class="absolute w-full text-right pr-4 text-[11px] text-gray-400 font-bold transform -translate-y-1/2" style="top: {{ ($i - 8) * 60 }}px">
                                        {{ $i > 12 ? ($i == 24 ? 12 : $i - 12) : ($i == 0 ? 12 : $i) }}{{ $i >= 12 && $i < 24 ? 'PM' : 'AM' }}
                                    </div>
                                @endfor
                            </div>
                            
                            <!-- Day Columns -->
                            @foreach($weekDays as $day)
                                <div class="relative h-[960px]" x-show="viewMode === 'week' || selectedDayFilter === '{{ $day->format('Y-m-d') }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                                    <!-- Vertical Column Background -->
                                    <div class="absolute inset-0 bg-white/40 rounded-2xl border border-gray-100"></div>

                                    <!-- Grid lines (Horizontal) -->
                                    @for($i = 8; $i <= 24; $i++)
                                        <div class="absolute w-full border-t border-gray-200/40" style="top: {{ ($i - 8) * 60 }}px"></div>
                                    @endfor
                                    
                                    <!-- Events -->
                                    @php $dayFormatted = $day->format('Y-m-d'); @endphp
                                    @if(isset($groupedItems[$dayFormatted]))
                                        @foreach($groupedItems[$dayFormatted] as $item)
                                            @php
                                                $start = \Carbon\Carbon::parse($item->start_time);
                                                $startHour = $start->hour;
                                                $startMinute = $start->minute;
                                                $duration = $item->duration_minutes;
                                                
                                                // Constrain to 8 AM - Midnight view
                                                if ($startHour >= 8 && $startHour <= 23) {
                                                    $topOffset = (($startHour - 8) * 60) + $startMinute;
                                                    $height = max($duration, 30); // 1px = 1 min, min 30px height
                                                    
                                                    // Assign colors based on subject ID to match reference image vibrancy
                                                    $colors = [
                                                        ['bg' => 'bg-blue-500', 'text' => 'text-white'],
                                                        ['bg' => 'bg-emerald-500', 'text' => 'text-white'],
                                                        ['bg' => 'bg-purple-500', 'text' => 'text-white'],
                                                        ['bg' => 'bg-orange-400', 'text' => 'text-white'],
                                                        ['bg' => 'bg-rose-500', 'text' => 'text-white']
                                                    ];
                                                    $subjectId = $item->subject_id ?? 0;
                                                    $color = $colors[$subjectId % count($colors)];
                                                    
                                                    if ($item->status == 'completed') {
                                                        $color = ['bg' => 'bg-green-100', 'text' => 'text-green-700'];
                                                    }
                                            @endphp
                                            
                                            <div class="absolute left-1.5 right-1.5 rounded-xl shadow-sm p-2.5 overflow-hidden transition-all hover:z-20 hover:scale-[1.02] hover:shadow-lg {{ $color['bg'] }} {{ $color['text'] }} group"
                                                 style="top: {{ $topOffset }}px; height: {{ $height }}px;">
                                                
                                                @if($item->status == 'completed')
                                                    <!-- Completed State -->
                                                    <div class="flex items-center justify-center h-full">
                                                        <svg class="w-10 h-10 text-green-500 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                @else
                                                    <!-- Upcoming State -->
                                                    <h4 class="text-[13px] leading-tight font-bold truncate pr-5">{{ $item->subject?->name ?? 'Uncategorized' }}</h4>
                                                    <p class="text-[11px] font-semibold opacity-90 truncate mt-0.5">{{ $start->format('g:i A') }} - {{ $start->copy()->addMinutes($duration)->format('g:i A') }}</p>
                                                    
                                                    <!-- Icon -->
                                                    <div class="absolute top-2 right-2">
                                                        <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    </div>
                                                    
                                                    <!-- Hover actions overlay -->
                                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[1px]">
                                                        <form action="{{ route('study-plans.sessions.complete', $item->id) }}" method="POST" class="m-0">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="w-7 h-7 flex items-center justify-center bg-white text-green-600 rounded-full shadow-md hover:bg-green-50 transition-colors" title="Mark Complete">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                            </button>
                                                        </form>
                                                        <button @click="showEditModal = true; editItem = { id: {{ $item->id }}, title: '{{ addslashes($item->title) }}', subject_id: '{{ $item->subject_id }}', start_time: '{{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}', end_time: '{{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}' }" class="w-7 h-7 flex items-center justify-center bg-white text-blue-600 rounded-full shadow-md hover:bg-blue-50 transition-colors" title="Edit">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            @php } @endphp
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Add Manual Session Modal -->
        <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showAddModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showAddModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Add Manual Session</h3>
                            <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-500 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="px-4 py-5 sm:p-6">
                        <form id="addSessionForm" action="{{ route('study-plans.sessions.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="study_date" value="{{ $selectedDate }}">
                            
                            <div>
                                <x-input-label value="Subject" class="text-sm font-semibold text-gray-700" />
                                <select name="subject_id" class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label value="Title (e.g. Extra revision)" class="text-sm font-semibold text-gray-700" />
                                <x-text-input name="title" required class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="Start Time" class="text-sm font-semibold text-gray-700" />
                                    <x-text-input name="start_time" type="time" required class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <x-input-label value="End Time" class="text-sm font-semibold text-gray-700" />
                                    <x-text-input name="end_time" type="time" required class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="button" onclick="document.getElementById('addSessionForm').submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Save Session
                        </button>
                        <button type="button" @click="showAddModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Session Modal -->
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showEditModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showEditModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">Edit Session</h3>
                            <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-500 p-1 rounded-full hover:bg-gray-100 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="px-4 py-5 sm:p-6">
                        <form id="editSessionForm" :action="'{{ url('/study-plans/sessions') }}/' + editItem.id" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="study_date" value="{{ $selectedDate }}">
                            
                            <div>
                                <x-input-label value="Subject" class="text-sm font-semibold text-gray-700" />
                                <select name="subject_id" x-model="editItem.subject_id" class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label value="Title" class="text-sm font-semibold text-gray-700" />
                                <x-text-input name="title" x-model="editItem.title" required class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label value="Start Time" class="text-sm font-semibold text-gray-700" />
                                    <x-text-input name="start_time" type="time" x-model="editItem.start_time" required class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                </div>
                                <div>
                                    <x-input-label value="End Time" class="text-sm font-semibold text-gray-700" />
                                    <x-text-input name="end_time" type="time" x-model="editItem.end_time" required class="block w-full mt-1 border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="button" onclick="document.getElementById('editSessionForm').submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Update Session
                        </button>
                        <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>