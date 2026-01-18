<div class="max-w-2xl mx-auto p-4">
    @if (!$question && !$gameFinished)
        <div class="text-center py-12">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Loading Quiz...</h2>
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto"></div>
        </div>
    @elseif($gameFinished)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden text-center p-8 animate-fade-in-up">
            <div class="mb-6">
                <span class="text-6xl">🏁</span>
            </div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2">Quiz Complete!</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Here is how you performed:</p>

            <div class="text-5xl font-bold text-red-600 mb-2">
                {{ $score }} / {{ count($questions) }}
            </div>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-8">Final Score</p>

            <button wire:click="startNewGame"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-xl transition-all active:scale-95 shadow-lg shadow-red-600/30">
                Play Again
            </button>
        </div>
    @else
        <div class="mb-6 flex justify-between items-center">
            <span class="text-sm font-bold text-gray-500">Question {{ $currentIndex + 1 }} /
                {{ count($questions) }}</span>
            <span
                class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-0.5 rounded-full dark:bg-red-900/30 dark:text-red-400">
                {{ ucfirst($question->difficulty) }}
            </span>
        </div>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
            <!-- Question Header -->
            <div
                class="p-6 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $question->question }}
                </h3>
            </div>

            <!-- Options -->
            <div class="p-4 space-y-3">
                @foreach ($question->options as $option)
                    @php
                        $isSelected = $selectedAnswer === $option;
                        $isCorrect = $option === $question->correct_answer;
                        $showResult = $selectedAnswer !== null;

                        $baseClasses =
                            'w-full text-left p-4 rounded-xl border-2 font-medium transition-all duration-200 flex items-center justify-between group';

                        if (!$showResult) {
                            $classes =
                                $baseClasses .
                                ' border-gray-200 hover:border-red-600 hover:bg-red-50 dark:border-gray-600 dark:hover:border-red-500 dark:hover:bg-red-900/20';
                        } elseif ($isCorrect) {
                            $classes =
                                $baseClasses .
                                ' border-green-500 bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-400';
                        } elseif ($isSelected && !$isCorrect) {
                            $classes =
                                $baseClasses .
                                ' border-red-500 bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400';
                        } else {
                            $classes = $baseClasses . ' border-gray-200 opacity-50 dark:border-gray-700';
                        }
                    @endphp

                    <button wire:click="selectAnswer('{{ addslashes($option) }}')"
                        @if ($showResult) disabled @endif class="{{ $classes }}">

                        <span>{{ $option }}</span>

                        @if ($showResult)
                            @if ($isCorrect)
                                <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            @elseif($isSelected)
                                <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        @else
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 group-hover:border-red-500"></div>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Explanation & Next Button -->
            @if ($showExplanation)
                <div
                    class="p-6 bg-blue-50 dark:bg-blue-900/20 border-t border-blue-100 dark:border-blue-800 animate-fade-in-up">
                    <div class="flex items-start mb-4">
                        <div class="shrink-0 mr-3 mt-1 text-blue-500">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 dark:text-blue-100 text-sm uppercase tracking-wide mb-1">
                                Did you know?</h4>
                            <p class="text-blue-800 dark:text-blue-200 leading-relaxed">{{ $question->explanation }}</p>
                        </div>
                    </div>

                    <button wire:click="nextQuestion"
                        class="w-full bg-gray-900 dark:bg-white dark:text-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-xl transition-colors flex items-center justify-center">
                        <span>{{ $currentIndex < count($questions) - 1 ? 'Next Question' : 'See Results' }}</span>
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
