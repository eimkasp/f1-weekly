<div>
    @if ($race)
        @php
            $sizeClasses = match ($size) {
                'compact' => 'p-4 rounded-lg',
                'large' => 'p-8 rounded-2xl',
                default => 'p-6 rounded-xl',
            };

            $flagSize = match ($size) {
                'compact' => 'w-8 h-6',
                'large' => 'w-16 h-12',
                default => 'w-12 h-8',
            };

            $titleSize = match ($size) {
                'compact' => 'text-lg',
                'large' => 'text-3xl',
                default => 'text-2xl',
            };

            $countdownSize = match ($size) {
                'compact' => 'text-xl',
                'large' => 'text-5xl',
                default => 'text-3xl',
            };
        @endphp

        <a href="{{ $this->raceUrl }}"
            class="block bg-gradient-to-br from-red-900/50 to-gray-900 {{ $sizeClasses }} border border-red-800/30 hover:border-red-600/50 transition-all duration-300 group">

            <!-- Header with Flag -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <!-- Country Flag -->
                    <img src="{{ $this->flagUrl }}" alt="{{ $race->circuit?->country }} flag"
                        class="{{ $flagSize }} object-cover rounded shadow-lg"
                        onerror="this.src='https://flagcdn.com/w160/un.png'">
                    <div>
                        <span class="text-red-500 text-xs font-semibold uppercase tracking-wider">Next Race</span>
                        <span class="text-gray-500 mx-2">•</span>
                        <span class="text-gray-400 text-xs">Round {{ $race->round }}</span>
                    </div>
                </div>

                <!-- Arrow indicator -->
                <svg class="w-5 h-5 text-gray-500 group-hover:text-red-500 group-hover:translate-x-1 transition-all"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </div>

            <!-- Race Name -->
            <h3 class="{{ $titleSize }} font-bold text-white mb-2 group-hover:text-red-400 transition-colors">
                {{ $race->name }}
            </h3>

            <!-- Location -->
            @if ($race->circuit)
                <div class="flex items-center text-gray-400 mb-4">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ $race->circuit->name }}, {{ $race->circuit->country }}</span>
                </div>
            @endif

            <!-- Countdown Timer -->
            <div x-data="raceCountdown('{{ $this->raceDateIso }}')" x-init="startCountdown()" class="grid grid-cols-4 gap-2 md:gap-4 mt-6">
                <div class="text-center bg-gray-800/50 rounded-lg p-3">
                    <div class="{{ $countdownSize }} font-bold text-white font-mono" x-text="days">--</div>
                    <div class="text-gray-500 text-xs uppercase tracking-wider">Days</div>
                </div>
                <div class="text-center bg-gray-800/50 rounded-lg p-3">
                    <div class="{{ $countdownSize }} font-bold text-white font-mono" x-text="hours">--</div>
                    <div class="text-gray-500 text-xs uppercase tracking-wider">Hours</div>
                </div>
                <div class="text-center bg-gray-800/50 rounded-lg p-3">
                    <div class="{{ $countdownSize }} font-bold text-white font-mono" x-text="minutes">--</div>
                    <div class="text-gray-500 text-xs uppercase tracking-wider">Mins</div>
                </div>
                <div class="text-center bg-gray-800/50 rounded-lg p-3">
                    <div class="{{ $countdownSize }} font-bold text-red-500 font-mono" x-text="seconds">--</div>
                    <div class="text-gray-500 text-xs uppercase tracking-wider">Secs</div>
                </div>
            </div>

            <!-- Race Details -->
            @if ($showDetails)
                <div class="mt-6 pt-4 border-t border-gray-700/50 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div class="text-gray-500 text-xs uppercase tracking-wider mb-1">Race Start</div>
                        <div class="text-white text-sm">
                            {{ $race->race_date->format('l, F j, Y') }}
                        </div>
                        <div class="text-gray-400 text-sm">
                            {{ $race->race_date->format('H:i') }} UTC
                        </div>
                    </div>

                    @if ($race->is_sprint_weekend)
                        <div
                            class="flex items-center bg-yellow-600/20 text-yellow-500 px-3 py-1 rounded-full text-xs font-semibold">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"
                                    clip-rule="evenodd" />
                            </svg>
                            Sprint Weekend
                        </div>
                    @endif
                </div>
            @endif
        </a>
    @else
        <!-- No upcoming race -->
        <div class="bg-gray-900 {{ $sizeClasses }} border border-gray-800 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-gray-400">No upcoming races scheduled</p>
            <p class="text-gray-500 text-sm mt-1">Check back later for the new season</p>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        function raceCountdown(targetDate) {
            return {
                days: '--',
                hours: '--',
                minutes: '--',
                seconds: '--',
                targetDate: new Date(targetDate),
                interval: null,

                startCountdown() {
                    this.updateCountdown();
                    this.interval = setInterval(() => this.updateCountdown(), 1000);
                },

                updateCountdown() {
                    const now = new Date();
                    const diff = this.targetDate - now;

                    if (diff <= 0) {
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        if (this.interval) {
                            clearInterval(this.interval);
                        }
                        return;
                    }

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    this.days = String(days).padStart(2, '0');
                    this.hours = String(hours).padStart(2, '0');
                    this.minutes = String(minutes).padStart(2, '0');
                    this.seconds = String(seconds).padStart(2, '0');
                },

                destroy() {
                    if (this.interval) {
                        clearInterval(this.interval);
                    }
                }
            }
        }
    </script>
@endpush
