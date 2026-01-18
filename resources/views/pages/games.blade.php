<x-layouts.app title="F1 Arcade">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-8">F1 Arcade</h1>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Classic Trivia -->
            <a href="{{ route('quiz') }}"
                class="group relative block h-64 rounded-2xl overflow-hidden shadow-xl transform transition hover:-translate-y-2 hover:shadow-2xl">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800 mix-blend-multiply opacity-90 group-hover:opacity-100 transition">
                </div>
                <!-- You can add a background image here if you have one -->
                <div class="absolute inset-0 flex flex-col justify-end p-8">
                    <span class="text-blue-200 font-bold tracking-wider text-sm uppercase mb-2">Casual</span>
                    <h2 class="text-3xl font-black text-white mb-2">Daily Trivia</h2>
                    <p class="text-blue-100 font-medium">Test your knowledge with 5 varied questions. Learn something
                        new every day!</p>
                </div>
            </a>

            <!-- Survival Mode -->
            <a href="{{ route('survival') }}"
                class="group relative block h-64 rounded-2xl overflow-hidden shadow-xl transform transition hover:-translate-y-2 hover:shadow-2xl">
                <div
                    class="absolute inset-0 bg-gradient-to-br from-red-600 to-red-900 mix-blend-multiply opacity-90 group-hover:opacity-100 transition">
                </div>
                <div class="absolute inset-0 flex flex-col justify-end p-8">
                    <span
                        class="text-red-200 font-bold tracking-wider text-sm uppercase mb-2 animate-pulse">Hardcore</span>
                    <h2 class="text-3xl font-black text-white mb-2">Survival Mode</h2>
                    <p class="text-red-100 font-medium">One mistake and you're out. How long can you keep your streak
                        alive?</p>
                </div>
            </a>
        </div>
    </div>
</x-layouts.app>
