<div class="race-calendar">
    <!-- Next Race Highlight -->
    @if ($nextRace)
        <div class="next-race-card bg-gradient-to-br from-red-900/50 to-gray-900 rounded-xl p-6 mb-8 border-2 border-red-600/50 shadow-xl shadow-red-900/20"
            x-data="countdown('{{ $nextRace->race_date->toIso8601String() }}')">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span class="text-red-400 text-sm font-semibold uppercase tracking-wider">Next Race</span>
                </div>
                <span class="text-gray-400 text-sm">Round {{ $nextRace->round }}</span>
            </div>

            <h3 class="text-2xl md:text-3xl font-bold text-white mb-2">{{ $nextRace->name }}</h3>

            @if ($nextRace->circuit)
                <div class="flex items-center text-gray-300 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ $nextRace->circuit->name }}, {{ $nextRace->circuit->country }}</span>
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-6">
                <div class="text-center bg-black/30 rounded-lg p-4 border border-gray-800">
                    <div class="text-3xl md:text-4xl font-bold text-red-500 mb-1" x-text="days">0</div>
                    <div class="text-gray-400 text-xs uppercase font-semibold tracking-wider">Days</div>
                </div>
                <div class="text-center bg-black/30 rounded-lg p-4 border border-gray-800">
                    <div class="text-3xl md:text-4xl font-bold text-red-500 mb-1" x-text="hours">0</div>
                    <div class="text-gray-400 text-xs uppercase font-semibold tracking-wider">Hours</div>
                </div>
                <div class="text-center bg-black/30 rounded-lg p-4 border border-gray-800">
                    <div class="text-3xl md:text-4xl font-bold text-red-500 mb-1" x-text="minutes">0</div>
                    <div class="text-gray-400 text-xs uppercase font-semibold tracking-wider">Minutes</div>
                </div>
                <div class="text-center bg-black/30 rounded-lg p-4 border border-gray-800">
                    <div class="text-3xl md:text-4xl font-bold text-red-500 mb-1" x-text="seconds">0</div>
                    <div class="text-gray-400 text-xs uppercase font-semibold tracking-wider">Seconds</div>
                </div>
            </div>

            <div
                class="mt-6 pt-4 border-t border-gray-700 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-gray-400 text-sm mb-1">Race Start</div>
                    <div class="text-white font-semibold">
                        {{ $nextRace->race_date->format('l, F j, Y') }} at {{ $nextRace->race_date->format('H:i') }} UTC
                    </div>
                </div>
                <a href="{{ route('races.show', $nextRace) }}"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors inline-flex items-center justify-center gap-2">
                    <span>View Race Details</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="flex space-x-2 mb-6">
        <button wire:click="setFilter('all')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                       {{ $filter === 'all' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            All Races
        </button>
        <button wire:click="setFilter('upcoming')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                       {{ $filter === 'upcoming' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            Upcoming
        </button>
        <button wire:click="setFilter('completed')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                       {{ $filter === 'completed' ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            Completed
        </button>
    </div>

    <!-- Race List -->
    <div class="space-y-3">
        @forelse($races as $race)
            <a href="{{ route('race.show', $race) }}"
                class="block bg-gray-900 rounded-lg p-4 hover:bg-gray-800 transition-colors border border-gray-800">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="text-center w-12">
                            <div class="text-2xl font-bold text-white">{{ $race->round }}</div>
                            <div class="text-xs text-gray-500 uppercase">Round</div>
                        </div>

                        <div class="w-px h-12 bg-gray-700"></div>

                        <div>
                            <h4 class="text-white font-medium">{{ $race->name }}</h4>
                            @if ($race->circuit)
                                <div class="text-gray-500 text-sm">{{ $race->circuit->name }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-white text-sm">{{ $race->race_date->format('M j') }}</div>
                        <div class="text-gray-500 text-xs">{{ $race->race_date->format('Y') }}</div>

                        @if ($race->isCompleted())
                            <span
                                class="inline-flex items-center px-2 py-1 mt-1 rounded text-xs font-medium bg-green-900/50 text-green-400">
                                Completed
                            </span>
                        @elseif($race->isLive())
                            <span
                                class="inline-flex items-center px-2 py-1 mt-1 rounded text-xs font-medium bg-red-900/50 text-red-400 animate-pulse">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-1"></span>
                                Live
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 mt-1 rounded text-xs font-medium bg-gray-800 text-gray-400">
                                Upcoming
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="text-center py-12 text-gray-500">
                No races found for the selected filter.
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
    <script>
        function countdown(targetDate) {
            return {
                days: '--',
                hours: '--',
                minutes: '--',
                seconds: '--',
                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },
                updateCountdown() {
                    const target = new Date(targetDate).getTime();
                    const now = new Date().getTime();
                    const diff = target - now;

                    if (diff <= 0) {
                        this.days = '0';
                        this.hours = '0';
                        this.minutes = '0';
                        this.seconds = '0';
                        return;
                    }

                    this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                }
            };
        }
    </script>
@endpush
