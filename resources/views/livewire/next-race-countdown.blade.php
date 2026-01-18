<div wire:poll.1s class="w-full">
    @if ($race)
        <div
            class="bg-gray-900/80 backdrop-blur-sm rounded-2xl p-6 border border-gray-800 shadow-2xl relative overflow-hidden group">
            <!-- Background Image Effect -->
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-gray-900/90 to-transparent z-10"></div>
                <!-- If race has circuit image, could use it here -->
            </div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <!-- Race Info -->
                <div class="text-center md:text-left">
                    <div class="text-red-500 font-bold tracking-widest text-xs uppercase mb-2">Next Grand Prix</div>
                    <h2 class="text-3xl md:text-4xl font-black text-white italic leading-none mb-2">
                        {{ $race->name }}
                    </h2>
                    <div class="flex items-center justify-center md:justify-start gap-2 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">{{ $race->circuit->name ?? 'TBA' }}</span>
                        <span class="text-gray-600">•</span>
                        <span>{{ \Carbon\Carbon::parse($race->race_date)->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- Countdown Timer -->
                <div class="flex gap-4">
                    <div class="bg-black/50 rounded-lg p-3 w-16 md:w-20 text-center border border-gray-800">
                        <span
                            class="block text-2xl md:text-3xl font-bold text-white font-mono">{{ $timeRemaining['days'] ?? 0 }}</span>
                        <span class="text-xs text-gray-500 uppercase font-bold">Days</span>
                    </div>
                    <div class="bg-black/50 rounded-lg p-3 w-16 md:w-20 text-center border border-gray-800">
                        <span
                            class="block text-2xl md:text-3xl font-bold text-white font-mono">{{ sprintf('%02d', $timeRemaining['hours'] ?? 0) }}</span>
                        <span class="text-xs text-gray-500 uppercase font-bold">Hrs</span>
                    </div>
                    <div class="bg-black/50 rounded-lg p-3 w-16 md:w-20 text-center border border-gray-800">
                        <span
                            class="block text-2xl md:text-3xl font-bold text-white font-mono">{{ sprintf('%02d', $timeRemaining['minutes'] ?? 0) }}</span>
                        <span class="text-xs text-gray-500 uppercase font-bold">Mins</span>
                    </div>
                    <div
                        class="bg-black/50 rounded-lg p-3 w-16 md:w-20 text-center border border-red-500/50 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                        <span
                            class="block text-2xl md:text-3xl font-bold text-red-500 font-mono">{{ sprintf('%02d', $timeRemaining['seconds'] ?? 0) }}</span>
                        <span class="text-xs text-red-500/80 uppercase font-bold">Secs</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
