<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.0.6/purify.min.js"></script>

<style>
.markdown-content h1, .markdown-content h2, .markdown-content h3, .markdown-content h4 { font-weight: 700; margin-top: 0.75em; margin-bottom: 0.5em; color: #111827; }
.markdown-content h1 { font-size: 1.5rem; }
.markdown-content h2 { font-size: 1.25rem; }
.markdown-content h3 { font-size: 1.125rem; }
.markdown-content p { margin-bottom: 0.75em; line-height: 1.6; }
.markdown-content ul { list-style-type: disc; margin-left: 1.5em; margin-bottom: 0.75em; }
.markdown-content ol { list-style-type: decimal; margin-left: 1.5em; margin-bottom: 0.75em; }
.markdown-content li { margin-bottom: 0.25em; }
.markdown-content strong { font-weight: 700; color: #111827; }
.markdown-content code { background-color: #f3f4f6; padding: 0.125em 0.25em; border-radius: 0.25em; font-family: monospace; font-size: 0.875em; color: #ef4444; }
.markdown-content pre { background-color: #1f2937; color: #f9fafb; padding: 1em; border-radius: 0.5em; overflow-x: auto; margin-bottom: 0.75em; }
.markdown-content pre code { background-color: transparent; padding: 0; color: inherit; font-size: 0.875em; }
.markdown-content blockquote { border-left: 4px solid #e5e7eb; padding-left: 1em; color: #4b5563; font-style: italic; margin-bottom: 0.75em; }
.markdown-content table { width: 100%; border-collapse: collapse; margin-bottom: 0.75em; font-size: 0.875em; }
.markdown-content th, .markdown-content td { border: 1px solid #e5e7eb; padding: 0.5em; text-align: left; }
.markdown-content th { background-color: #f9fafb; font-weight: 600; }
.markdown-content a { color: #4f46e5; text-decoration: underline; }
.markdown-content *:last-child { margin-bottom: 0; }
</style>

@php
    $subjectData = [];
    if (auth()->check()) {
        $subjects = \App\Models\Subject::where('user_id', auth()->id())
            ->with(['assignments:id,subject_id,title', 'exams:id,subject_id,title'])
            ->get();
            
        $subjectData = $subjects->map(function($sub) {
            $assignmentTopics = $sub->assignments->pluck('title')->toArray();
            $examTopics = $sub->exams->pluck('title')->toArray();
            $allTopics = array_values(array_unique(array_merge($assignmentTopics, $examTopics)));
            return [
                'name' => $sub->name,
                'topics' => $allTopics
            ];
        });
    }
@endphp

<div 
    x-data="aiAssistant({{ $subjectData->toJson() }})" 
    class="fixed bottom-6 right-6 z-50 font-sans"
    x-cloak
>
    <!-- Floating Button -->
    <button 
        @click="toggle"
        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg flex items-center justify-center transition-transform hover:scale-105"
        :class="{ 'rotate-45 bg-gray-600 hover:bg-gray-700': open }"
    >
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.792 0-5.484-.44-8.135-1.287-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"></path></svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <!-- Assistant Panel -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="absolute bottom-16 right-0 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col"
        style="height: 600px; max-height: calc(100vh - 100px);"
    >
        <!-- Header -->
        <div class="bg-indigo-600 p-4 text-white">
            <h3 class="font-bold text-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                AI Learning Assistant
            </h3>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 bg-gray-50">
            <button @click="tab = 'ask'" :class="tab === 'ask' ? 'border-indigo-600 text-indigo-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-3 text-sm font-medium border-b-2 transition-colors">Ask AI</button>
            <button @click="tab = 'explain'" :class="tab === 'explain' ? 'border-indigo-600 text-indigo-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-3 text-sm font-medium border-b-2 transition-colors">Explain</button>
            <button @click="tab = 'quiz'" :class="tab === 'quiz' ? 'border-indigo-600 text-indigo-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'" class="flex-1 py-3 text-sm font-medium border-b-2 transition-colors">Quiz</button>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto p-4 bg-white relative">
            
            <!-- Loading Overlay -->
            <div x-show="loading" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10">
                <div class="flex flex-col items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-indigo-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-sm font-medium text-gray-600">AI is thinking...</span>
                </div>
            </div>

            <!-- Global Error -->
            <div x-show="error" class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4" x-text="error"></div>

            <!-- ASK AI TAB -->
            <div x-show="tab === 'ask'" class="h-full flex flex-col space-y-4">
                <div class="flex-1 overflow-y-auto space-y-4" id="ask-chat-container">
                    <template x-for="msg in askHistory">
                        <div :class="msg.role === 'user' ? 'text-right' : 'text-left'">
                            <div :class="msg.role === 'user' ? 'bg-indigo-100 text-indigo-900 rounded-bl-xl' : 'bg-gray-100 text-gray-800 rounded-br-xl'" class="inline-block p-3 rounded-t-xl max-w-[90%] text-sm text-left">
                                <template x-if="msg.role === 'user'">
                                    <div class="whitespace-pre-wrap" x-text="msg.text"></div>
                                </template>
                                <template x-if="msg.role === 'assistant'">
                                    <div class="markdown-content" x-html="renderMarkdown(msg.text)"></div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div x-show="askHistory.length === 0" class="text-center text-gray-400 mt-10">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        <p class="text-sm">Ask any educational question.</p>
                    </div>
                </div>
                <form @submit.prevent="submitAsk" class="mt-auto flex gap-2">
                    <input type="text" x-model="askInput" placeholder="Type your question..." class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit" :disabled="loading || !askInput.trim()" class="bg-indigo-600 text-white px-4 rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>

            <!-- EXPLAIN TAB -->
            <div x-show="tab === 'explain'" class="h-full flex flex-col space-y-4">
                <form @submit.prevent="submitExplain">
                    <label class="block text-sm font-medium text-gray-700 mb-1">What topic do you need explained?</label>
                    <div class="flex gap-2 mb-4">
                        <input type="text" x-model="explainInput" placeholder="e.g. Recursion, Photosynthesis..." class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <button type="submit" :disabled="loading || !explainInput.trim()" class="bg-indigo-600 text-white px-4 rounded-lg hover:bg-indigo-700 disabled:opacity-50">Go</button>
                    </div>
                </form>
                
                <div x-show="explainResult" class="flex-1 bg-gray-50 rounded-xl p-4 overflow-y-auto border border-gray-200">
                    <h4 class="font-bold text-indigo-900 mb-2 border-b pb-1">Explanation</h4>
                    <div class="text-sm text-gray-700 markdown-content" x-html="renderMarkdown(explainResult)"></div>
                </div>
                
                <div x-show="!explainResult" class="text-center text-gray-400 mt-10">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    <p class="text-sm">Get clear, simple explanations for complex topics.</p>
                </div>
            </div>

            <!-- QUIZ TAB -->
            <div x-show="tab === 'quiz'" class="h-full flex flex-col">
                <!-- Quiz Generator Form -->
                <form x-show="!quizActive" @submit.prevent="generateQuiz" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <select x-model="quizForm.subject" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="" disabled>Select Subject</option>
                            <template x-for="sub in subjects" :key="sub.name">
                                <option :value="sub.name" x-text="sub.name"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div x-show="quizForm.subject">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                        <select x-model="quizForm.topic" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Any Topic">Any Topic</option>
                            <template x-for="topic in availableTopics" :key="topic">
                                <option :value="topic" x-text="topic"></option>
                            </template>
                        </select>
                    </div>
                    
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty</label>
                            <select x-model="quizForm.difficulty" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option>Easy</option>
                                <option>Medium</option>
                                <option>Hard</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Questions</label>
                            <select x-model="quizForm.numQuestions" class="w-full rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="5">5</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" :disabled="loading" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 font-medium disabled:opacity-50 mt-4 shadow-sm">
                        Generate Quiz
                    </button>
                </form>

                <!-- Active Quiz -->
                <div x-show="quizActive && !quizSubmitted" class="flex flex-col h-full space-y-4">
                    <div class="flex justify-between items-center text-sm font-bold text-gray-700 bg-gray-50 p-3 rounded-lg border">
                        <span x-text="quizForm.topic"></span>
                        <span class="text-indigo-600">Q <span x-text="currentQuestionIndex + 1"></span> of <span x-text="quizData.length"></span></span>
                    </div>

                    <div class="flex-1 overflow-y-auto">
                        <template x-if="quizData[currentQuestionIndex]">
                            <div class="space-y-3">
                                <h4 class="font-bold text-gray-900 text-sm leading-relaxed" x-text="quizData[currentQuestionIndex].question"></h4>
                                <div class="space-y-2">
                                    <template x-for="(opt, idx) in quizData[currentQuestionIndex].options">
                                        <button 
                                            @click="selectOption(opt)"
                                            class="w-full text-left p-3 rounded-lg border text-sm transition-colors duration-150"
                                            :class="userAnswers[currentQuestionIndex] === opt ? 'border-indigo-500 bg-indigo-50 text-indigo-900 font-medium' : 'border-gray-200 hover:bg-gray-50 hover:border-gray-300 text-gray-700'"
                                        >
                                            <span class="mr-2 font-bold text-gray-400" x-text="['A','B','C','D'][idx]"></span>
                                            <span x-text="opt"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-between mt-auto pt-4 border-t">
                        <button @click="prevQuestion" :disabled="currentQuestionIndex === 0" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 disabled:opacity-50">Previous</button>
                        
                        <button x-show="currentQuestionIndex < quizData.length - 1" @click="nextQuestion" class="bg-gray-100 hover:bg-gray-200 text-gray-900 px-4 py-2 rounded-lg text-sm font-bold">Next</button>
                        
                        <button x-show="currentQuestionIndex === quizData.length - 1" @click="submitQuiz" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-sm">Submit Quiz</button>
                    </div>
                </div>

                <!-- Quiz Results -->
                <div x-show="quizSubmitted" class="flex flex-col h-full space-y-4">
                    <div class="text-center p-6 bg-gradient-to-br from-indigo-50 to-white rounded-xl border shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 mb-1">Quiz Completed!</h3>
                        <p class="text-sm text-gray-500 mb-4" x-text="quizForm.topic"></p>
                        <div class="flex justify-center items-end space-x-2">
                            <span class="text-4xl font-black" :class="quizScore.percentage >= 70 ? 'text-green-600' : (quizScore.percentage >= 50 ? 'text-amber-500' : 'text-red-500')" x-text="quizScore.percentage + '%'"></span>
                        </div>
                        <p class="text-sm font-bold text-gray-600 mt-2"><span x-text="quizScore.correct"></span> out of <span x-text="quizScore.total"></span> correct</p>
                    </div>

                    <div class="flex-1 overflow-y-auto space-y-6 px-1 pb-4">
                        <template x-for="(q, idx) in quizData">
                            <div class="space-y-2 border-b pb-4 last:border-0">
                                <p class="font-bold text-sm text-gray-900">
                                    <span class="text-gray-400 mr-1" x-text="(idx+1)+'.'"></span>
                                    <span x-text="q.question"></span>
                                </p>
                                
                                <!-- User's Answer -->
                                <div class="text-sm flex items-start space-x-2" :class="userAnswers[idx] === q.correct_answer ? 'text-green-700' : 'text-red-600'">
                                    <svg x-show="userAnswers[idx] === q.correct_answer" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <svg x-show="userAnswers[idx] !== q.correct_answer" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    <div>
                                        <span class="font-semibold">Your Answer:</span>
                                        <span x-text="userAnswers[idx] || 'No answer'"></span>
                                    </div>
                                </div>

                                <!-- Correct Answer (if wrong) -->
                                <div x-show="userAnswers[idx] !== q.correct_answer" class="text-sm flex items-start space-x-2 text-green-700 bg-green-50 p-2 rounded">
                                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <div>
                                        <span class="font-semibold">Correct Answer:</span>
                                        <span x-text="q.correct_answer"></span>
                                    </div>
                                </div>
                                
                                <!-- Explanation -->
                                <div class="bg-gray-50 text-gray-600 text-xs p-3 rounded-lg mt-2 italic border border-gray-100">
                                    <span class="font-bold not-italic">Explanation:</span> <span x-text="q.explanation"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button @click="resetQuiz" class="w-full bg-gray-100 text-gray-800 py-2 rounded-lg hover:bg-gray-200 font-bold mt-auto shrink-0 transition-colors">Start New Quiz</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('aiAssistant', (subjects = []) => ({
            open: false,
            tab: 'ask', // ask, explain, quiz
            loading: false,
            error: null,
            subjects: subjects,

            // Ask AI State
            askInput: '',
            askHistory: [],

            // Explain State
            explainInput: '',
            explainResult: null,

            // Quiz State
            quizForm: {
                subject: '',
                topic: 'Any Topic',
                difficulty: 'Medium',
                numQuestions: '5'
            },
            quizActive: false,
            quizSubmitted: false,
            quizData: [],
            currentQuestionIndex: 0,
            userAnswers: [],
            quizScore: { correct: 0, total: 0, percentage: 0 },

            get availableTopics() {
                if (!this.quizForm.subject) return [];
                const sub = this.subjects.find(s => s.name === this.quizForm.subject);
                return sub ? sub.topics : [];
            },

            init() {
                this.$watch('quizForm.subject', () => {
                    this.quizForm.topic = 'Any Topic';
                });
            },

            toggle() {
                this.open = !this.open;
            },

            renderMarkdown(text) {
                if (!text) return '';
                if (typeof marked === 'undefined' || typeof DOMPurify === 'undefined') {
                    // Fallback if CDNs fail to load
                    return text.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
                }
                const rawHtml = marked.parse(text);
                return DOMPurify.sanitize(rawHtml);
            },

            async apiCall(endpoint, payload) {
                this.loading = true;
                this.error = null;
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();
                    
                    if (!response.ok || !data.success) {
                        throw new Error(data.error || 'Something went wrong.');
                    }
                    
                    return data;
                } catch (err) {
                    this.error = err.message;
                    return null;
                } finally {
                    this.loading = false;
                }
            },

            async submitAsk() {
                if (!this.askInput.trim()) return;
                
                const question = this.askInput.trim();
                this.askHistory.push({ role: 'user', text: question });
                this.askInput = '';
                
                this.scrollToBottom('ask-chat-container');

                const res = await this.apiCall('/api/assistant/ask', { question });
                if (res && res.answer) {
                    this.askHistory.push({ role: 'assistant', text: res.answer });
                    this.scrollToBottom('ask-chat-container');
                }
            },

            async submitExplain() {
                if (!this.explainInput.trim()) return;
                
                const res = await this.apiCall('/api/assistant/explain', { topic: this.explainInput.trim() });
                if (res && res.explanation) {
                    this.explainResult = res.explanation;
                }
            },

            async generateQuiz() {
                const res = await this.apiCall('/api/assistant/quiz', this.quizForm);
                if (res && res.quiz && Array.isArray(res.quiz)) {
                    this.quizData = res.quiz;
                    this.quizActive = true;
                    this.quizSubmitted = false;
                    this.currentQuestionIndex = 0;
                    this.userAnswers = new Array(this.quizData.length).fill(null);
                }
            },

            selectOption(opt) {
                this.userAnswers[this.currentQuestionIndex] = opt;
            },

            nextQuestion() {
                if (this.currentQuestionIndex < this.quizData.length - 1) {
                    this.currentQuestionIndex++;
                }
            },

            prevQuestion() {
                if (this.currentQuestionIndex > 0) {
                    this.currentQuestionIndex--;
                }
            },

            submitQuiz() {
                let correct = 0;
                for (let i = 0; i < this.quizData.length; i++) {
                    if (this.userAnswers[i] === this.quizData[i].correct_answer) {
                        correct++;
                    }
                }
                
                this.quizScore = {
                    correct,
                    total: this.quizData.length,
                    percentage: Math.round((correct / this.quizData.length) * 100)
                };
                
                this.quizSubmitted = true;
            },

            resetQuiz() {
                this.quizActive = false;
                this.quizSubmitted = false;
                this.quizData = [];
                this.userAnswers = [];
                this.error = null;
            },

            scrollToBottom(id) {
                setTimeout(() => {
                    const el = document.getElementById(id);
                    if (el) el.scrollTop = el.scrollHeight;
                }, 50);
            }
        }));
    });
</script>
