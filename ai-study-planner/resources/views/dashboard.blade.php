<x-app-layout>
    <div x-data="{ notificationsOpen: false }" class="relative overflow-x-hidden min-h-screen">

        <!-- Notification Toggle Button (Floating on the left) -->
        <button @click="notificationsOpen = true" x-show="!notificationsOpen" class="fixed left-0 top-1/3 -translate-y-1/2 z-40 bg-indigo-600 text-white p-3 rounded-r-xl shadow-lg hover:bg-indigo-700 transition-colors flex flex-col items-center gap-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            @if(isset($smartAlerts) && $smartAlerts->count() > 0)
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $smartAlerts->count() }}</span>
            @endif
        </button>

        <!-- Slide-out Drawer Panel -->
        <div x-show="notificationsOpen" 
             class="fixed inset-y-0 left-0 w-80 sm:w-96 bg-white shadow-xl border-r border-gray-100 z-50 transform transition-transform duration-300 ease-in-out flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             style="display: none;">
             
            <!-- Drawer Header -->
            <div class="p-4 border-b flex justify-between items-center bg-gray-50">
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Notifications
                    @if(isset($smartAlerts) && $smartAlerts->count() > 0)
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $smartAlerts->count() }}</span>
                    @endif
                </h2>
                <button @click="notificationsOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-md hover:bg-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Drawer Content (Alerts) -->
            <div class="flex-1 overflow-y-auto p-5 space-y-4 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-gray-200 [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-gray-300">
                @if(isset($smartAlerts) && $smartAlerts->count() > 0)
                    @foreach($smartAlerts as $alert)
                        @php
                            $alertColor = match($alert->type) {
                                'high_risk' => 'bg-red-50 border-red-200 text-red-800',
                                'assignment_due' => 'bg-amber-50 border-amber-200 text-amber-800',
                                'missed_session' => 'bg-orange-50 border-orange-200 text-orange-800',
                                'exam_approaching' => 'bg-indigo-50 border-indigo-200 text-indigo-800',
                                default => 'bg-blue-50 border-blue-200 text-blue-800'
                            };
                            $iconColor = match($alert->type) {
                                'high_risk' => 'text-red-500',
                                'assignment_due' => 'text-amber-500',
                                'missed_session' => 'text-orange-500',
                                'exam_approaching' => 'text-indigo-500',
                                default => 'text-blue-500'
                            };
                        @endphp
                        <div class="flex flex-col p-4 border rounded-xl shadow-sm gap-3 {{ $alertColor }} hover:shadow-md transition-shadow">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 mt-0.5 shrink-0 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium leading-relaxed break-words">
                                        {{ $alert->message }}
                                    </p>
                                    @if($alert->action_url)
                                        <a href="{{ $alert->action_url }}" class="inline-block mt-1.5 text-xs underline font-bold hover:opacity-80">View Details &rarr;</a>
                                    @endif
                                </div>
                            </div>
                            <form method="POST" action="{{ route('alerts.read', $alert->id) }}" class="self-end mt-1">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold uppercase tracking-wider bg-white border border-gray-200 text-gray-700 hover:text-gray-900 px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow hover:bg-gray-50">
                                    Dismiss
                                </button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 font-medium">No new notifications.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Dashboard Content (Compresses left space to slide content right without overflowing) -->
        <div :class="notificationsOpen ? 'lg:pl-80 xl:pl-96' : 'pl-0'" class="transition-[padding] duration-300 ease-in-out w-full">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 pt-6 pb-12">
            
            <!-- AI Summary Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- 1. Predicted Exam Score -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Predicted Score</h4>
                <div class="flex items-end space-x-2">
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $predictions ? number_format($predictions->predicted_exam_score, 1) . '%' : '--' }}
                    </p>
                </div>
            </div>

            <!-- 2. Academic Risk -->
            @php
                $risk = $predictions ? strtolower($predictions->academic_risk) : 'none';
                $riskColor = match($risk) {
                    'high' => 'text-red-600',
                    'medium' => 'text-amber-500',
                    'low' => 'text-green-500',
                    default => 'text-gray-400'
                };
                $riskBg = match($risk) {
                    'high' => 'bg-red-50',
                    'medium' => 'bg-amber-50',
                    'low' => 'bg-green-50',
                    default => 'bg-gray-50'
                };
            @endphp
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-16 h-16 {{ $riskBg }} rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Academic Risk</h4>
                <p class="text-3xl font-bold uppercase {{ $riskColor }}">
                    {{ $predictions ? $predictions->academic_risk : '--' }}
                </p>
            </div>

            <!-- 3. Student Profile / Cluster -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-16 h-16 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Student Profile</h4>
                <p class="text-xl font-bold text-indigo-600 leading-tight">
                    {{ $predictions ? $predictions->cluster_label : 'Not Analyzed' }}
                </p>
            </div>

            <!-- 4. Recommendation Priority (Pending count) -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-16 h-16 bg-purple-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Recommendations</h4>
                <div class="flex items-center space-x-2">
                    <p class="text-3xl font-bold text-gray-900">
                        {{ Auth::user()->recommendations()->where('is_completed', false)->count() }}
                    </p>
                    <span class="text-sm font-medium text-gray-500">pending</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Main Content (Left Column) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Today's Study Sessions -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Today's Study Plan</h3>
                        <a href="{{ route('study-plans.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">View Full Schedule &rarr;</a>
                    </div>
                    
                    <div class="p-6">
                        @if($activePlan)
                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm font-semibold text-gray-600">Daily Progress</p>
                                    <span class="text-sm font-bold text-green-600">{{ $planProgress }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                  <div class="bg-green-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $planProgress }}%"></div>
                                </div>
                            </div>
                            
                            @if(count($todaySessions) > 0)
                                <div class="space-y-4">
                                    @foreach($todaySessions as $session)
                                        <div class="flex items-stretch gap-4 group">
                                            <!-- Timeline line -->
                                            <div class="flex flex-col items-center">
                                                <div class="w-3 h-3 rounded-full mt-1.5 {{ $session->status == 'completed' ? 'bg-green-500 ring-4 ring-green-100' : 'bg-indigo-500 ring-4 ring-indigo-100' }}"></div>
                                                <div class="w-0.5 h-full {{ $session->status == 'completed' ? 'bg-green-100' : 'bg-indigo-100' }} mt-2 group-last:hidden"></div>
                                            </div>
                                            <!-- Content card -->
                                            <div class="flex-1 p-4 rounded-xl border {{ $session->status == 'completed' ? 'bg-gray-50 border-gray-200 opacity-60' : 'bg-white border-gray-200 shadow-sm' }} transition-all">
                                                <div class="flex justify-between items-start mb-1">
                                                    <h4 class="font-bold text-gray-900 {{ $session->status == 'completed' ? 'line-through' : '' }}">{{ $session->subject?->name ?? 'Uncategorized' }}</h4>
                                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">
                                                        {{ \Carbon\Carbon::parse($session->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('g:i A') }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-600">{{ $session->title }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">No study sessions scheduled for today.</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 font-medium mb-4">No active study plan found.</p>
                                <a href="{{ route('study-plans.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition-colors">
                                    Go to Study Plan
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Recommendations -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-900">Recent AI Recommendations</h3>
                        <a href="{{ route('recommendations.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">View All &rarr;</a>
                    </div>
                    
                    <div class="p-6">
                        @if($recentRecommendations && $recentRecommendations->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentRecommendations as $rec)
                                    <div class="p-4 rounded-xl border border-gray-100 bg-white hover:bg-gray-50 transition-colors flex items-start space-x-4">
                                        <div class="mt-0.5 shrink-0">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold uppercase rounded-md
                                                {{ $rec->priority == 'high' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $rec->priority == 'medium' ? 'bg-amber-100 text-amber-700' : '' }}
                                                {{ $rec->priority == 'low' ? 'bg-green-100 text-green-700' : '' }}">
                                                {{ $rec->priority }}
                                            </span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700 leading-relaxed">{{ $rec->recommendation_text }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-gray-500 text-sm font-medium">No recent recommendations available. Generate some using the AI engine!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Side Content (Right Column) -->
            <div class="space-y-8">
                
                <!-- Upcoming Assignments -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-900">Pending Assignments ({{ $assignments->count() }})</h3>
                    </div>
                    <div class="p-2">
                        @if($assignments->count() > 0)
                            <div class="divide-y divide-gray-50">
                            @foreach($assignments as $assignment)
                                <div class="p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="font-bold text-gray-900 text-sm truncate pr-2">{{ $assignment->title }}</p>
                                        @if($assignment->priority == 'high')
                                            <span class="shrink-0 w-2 h-2 rounded-full bg-red-500 mt-1.5" title="High Priority"></span>
                                        @endif
                                    </div>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-indigo-600 font-medium">{{ $assignment->subject?->name ?? 'Uncategorized' }}</span>
                                        <span class="text-gray-500">Due {{ \Carbon\Carbon::parse($assignment->deadline)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        @else
                            <div class="p-6 text-center">
                                <p class="text-gray-500 text-sm font-medium">No pending assignments. Great job!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upcoming Exams -->
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-bold text-gray-900">Upcoming Exams ({{ $exams->count() }})</h3>
                    </div>
                    <div class="p-2">
                        @if($exams->count() > 0)
                            <div class="divide-y divide-gray-50">
                            @foreach($exams as $exam)
                                @php
                                    $daysRemaining = now()->diffInDays(\Carbon\Carbon::parse($exam->exam_date), false);
                                @endphp
                                <div class="p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="font-bold text-gray-900 text-sm truncate pr-2">{{ $exam->title }}</p>
                                        <span class="shrink-0 text-xs font-bold {{ $daysRemaining <= 7 ? 'text-red-600 bg-red-50' : 'text-indigo-600 bg-indigo-50' }} px-2 py-0.5 rounded">
                                            {{ \Carbon\Carbon::parse($exam->exam_date)->format('M d') }}
                                        </span>
                                    </div>
                                    <p class="text-xs font-medium text-gray-500">{{ $exam->subject?->name ?? 'Uncategorized' }}</p>
                                </div>
                            @endforeach
                            </div>
                        @else
                            <div class="p-6 text-center">
                                <p class="text-gray-500 text-sm font-medium">No upcoming exams scheduled.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
        </div>
        </div>
    </div>
</x-app-layout>