<div class="max-w-2xl mx-auto p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black italic text-red-600 tracking-tighter uppercase">Survival Mode</h1>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">One mistake and you're out</p>
        </div>
        <div class="text-right">
            <div class="text-xs font-bold text-gray-500 uppercase">Current Streak</div>
            <div class="text-4xl font-black text-gray-900 dark:text-white">{{ $score }}</div>
        </div>
    </div>

    @if (!$currentQuestion && !$gameOver)
        <div class="text-center py-12">
            <div class="animate-pulse text-red-600 font-bold text-xl">LOCATING NEXT CHALLENGE...</div>
        </div>
    @elseif($gameOver && !$currentQuestion)
        <!-- "You beat the game" case -->
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl shadow-xl p-8 text-center text-white">
            <h2 class="text-4xl font-black mb-4">LEGENDARY!</h2>
            <p class="font-medium text-lg mb-8">You answered every single question in our database.</p>
            <div class="text-6xl font-black mb-2">{{ $score }}</div>
            <p class="uppercase tracking-widest text-sm opacity-75 mb-8">Final Streak</p>
            <button wire:click="startNewGame"
                class="bg-white text-yellow-600 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-yellow-50 transition-colors">
                Play Again
            </button>
        </div>
    @elseif($gameOver && $selectedAnswer !== $correctAnswer)
        <!-- Game Over case -->
        <div class="bg-red-600 rounded-2xl shadow-xl p-8 text-center text-white animate-shake">
            <div class="mb-6 text-6xl">💥</div>
            <h2 class="text-4xl font-black mb-2">CRASHED OUT!</h2>
            <p class="font-medium text-red-100 mb-8">That was the wrong answer.</p>

            <div class="bg-red-800/50 rounded-xl p-6 mb-8 text-left">
                <p class="text-xs uppercase text-red-300 font-bold mb-1">The Question Was</p>
                <p class="font-bold text-lg mb-4">{{ $currentQuestion->question }}</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs uppercase text-red-300 font-bold mb-1">You Picked</p>
                        <p class="font-medium line-through decoration-2 opacity-75">{{ $selectedAnswer }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-green-300 font-bold mb-1">Correct Answer</p>
                        <p class="font-bold text-green-300">{{ $correctAnswer }}</p>
                    </div>
                </div>
            </div>

            <div class="text-5xl font-black mb-2">{{ $score }}</div>
            <p class="uppercase tracking-widest text-sm opacity-75 mb-8">Final Streak</p>

            <button wire:click="startNewGame"
                class="w-full bg-white text-red-600 font-bold py-4 px-6 rounded-xl shadow-lg hover:bg-gray-100 transition-colors">
                Try Again
            </button>
        </div>
    @else
        <!-- Active Game -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border-2 border-gray-900 dark:border-gray-600">
            <div class="p-8 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white leading-tight">
                    {{ $currentQuestion->question }}
                </h3>
            </div>

            <div class="p-4 space-y-3 bg-gray-50 dark:bg-gray-900/50">
                @foreach ($currentQuestion->options as $option)
                    @php
                        $isSelected = $selectedAnswer === $option;
                        $isCorrect = $option === $currentQuestion->correct_answer;
                        $showResult = $selectedAnswer !== null;

                        $baseClasses =
                            'w-full text-left p-5 rounded-xl border-4 font-bold text-lg transition-all duration-150 flex items-center justify-between transform';

                        if (!$showResult) {
                            $classes =
                                $baseClasses .
                                ' border-gray-200 bg-white hover:border-gray-900 hover:-translate-y-1 active:translate-y-0 active:scale-95 text-gray-600 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:border-white';
                        } elseif ($isCorrect) {
                            $classes =
                                $baseClasses .
                                ' border-green-500 bg-green-50 text-green-700 dark:bg-green-900 dark:text-green-400';
                        } elseif ($isSelected && !$isCorrect) {
                            $classes =
                                $baseClasses .
                                ' border-red-500 bg-red-50 text-red-700 dark:bg-red-900 dark:text-red-400';
                        } else {
                            $classes = $baseClasses . ' border-gray-100 opacity-40 grayscale';
                        }
                    @endphp

                    <button wire:click="selectAnswer('{{ addslashes($option) }}')"
                        @if ($showResult) disabled @endif class="{{ $classes }}">

                        <span>{{ $option }}</span>

                        @if ($showResult && ($isCorrect || $isSelected))
                            @if ($isCorrect)
                                <span class="text-2xl">✅</span>
                            @else
                                <span class="text-2xl">❌</span>
                            @endif
                        @endif
                    </button>
                @endforeach
            </div>

            @if ($selectedAnswer && !$gameOver)
                <div class="p-6 bg-green-500 text-white flex justify-between items-center animate-fade-in-up">
                    <div>
                        <span class="font-bold text-lg">Correct!</span>
                        <div class="text-xs opacity-90">Streak continued...</div>
                    </div>
                    <button wire:click="loadNextQuestion"
                        class="bg-white text-green-600 font-bold py-2 px-6 rounded-lg shadow-md hover:bg-green-50 transition-colors">
                        Next Question →
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
