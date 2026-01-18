<div class="live-race-tracker" wire:poll.5s="refreshData">
    @if ($race && $isLive)
        <!-- Live Header -->
        <div class="bg-gradient-to-r from-red-900 to-red-700 rounded-xl p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <span
                        class="flex items-center px-3 py-1 bg-red-500 text-white text-sm font-bold rounded-full animate-pulse">
                        <span class="w-2 h-2 bg-white rounded-full mr-2 animate-ping"></span>
                        LIVE
                    </span>
                    <span class="text-white/80 text-sm uppercase">{{ ucfirst($sessionType ?? 'Race') }}</span>
                </div>

                @if ($currentLap && $totalLaps)
                    <div class="text-white">
                        <span class="text-2xl font-bold">{{ $currentLap }}</span>
                        <span class="text-white/60">/{{ $totalLaps }} Laps</span>
                    </div>
                @endif
            </div>

            <h2 class="text-2xl font-bold text-white">{{ $race->name }}</h2>
            @if ($race->circuit)
                <p class="text-white/70">{{ $race->circuit->name }}, {{ $race->circuit->country }}</p>
            @endif
        </div>

        <!-- Live Positions -->
        <div class="bg-gray-900 rounded-xl overflow-hidden border border-gray-800">
            <div class="p-4 border-b border-gray-800">
                <h3 class="text-lg font-bold text-white">Live Positions</h3>
            </div>

            <div class="divide-y divide-gray-800">
                @forelse($positions as $pos)
                    @php
                        $driverInfo = $this->getDriverByNumber($pos['driver_number'] ?? 0);
                    @endphp
                    <div class="flex items-center justify-between p-4 hover:bg-gray-800/50 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center font-bold
                                @if (($pos['position'] ?? 99) === 1) bg-yellow-500 text-black
                                @elseif(($pos['position'] ?? 99) === 2) bg-gray-400 text-black
                                @elseif(($pos['position'] ?? 99) === 3) bg-amber-700 text-white
                                @else bg-gray-700 text-white @endif">
                                {{ $pos['position'] ?? '-' }}
                            </div>

                            <div>
                                <div class="text-white font-medium">
                                    {{ $driverInfo['name'] ?? 'Driver #' . ($pos['driver_number'] ?? '?') }}
                                </div>
                                <div class="text-gray-500 text-sm">
                                    {{ $driverInfo['team'] ?? 'Team' }}
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            @if (isset($pos['gap_to_leader']))
                                <div class="text-gray-400">
                                    @if ($pos['position'] === 1)
                                        <span class="text-green-500">Leader</span>
                                    @else
                                        +{{ number_format($pos['gap_to_leader'], 3) }}s
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <div
                            class="animate-spin w-8 h-8 border-2 border-red-500 border-t-transparent rounded-full mx-auto mb-4">
                        </div>
                        Waiting for live timing data...
                    </div>
                @endforelse
            </div>
        </div>
    @elseif($race && !$isLive)
        <!-- Not Live - Show Race Info -->
        <div class="bg-gray-900 rounded-xl p-6 border border-gray-800">
            <div class="text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-white mb-2">{{ $race->name }}</h3>

                @if ($race->isUpcoming())
                    <p class="text-gray-400 mb-4">Race starts {{ $race->race_date->diffForHumans() }}</p>
                    <div class="text-gray-500">
                        {{ $race->race_date->format('l, F j, Y') }} at {{ $race->race_date->format('H:i') }} UTC
                    </div>
                @else
                    <p class="text-gray-400 mb-4">Race completed</p>
                    <a href="{{ route('race.show', $race) }}"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        View Results
                    </a>
                @endif
            </div>
        </div>
    @else
        <!-- No Live Session - Compact Banner -->
        <div class="bg-gray-900/50 rounded-lg px-4 py-3 border border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-sm text-gray-400">No live session</span>
                    <span class="text-gray-600 mx-2">·</span>
                    <a href="{{ route('calendar') }}" class="text-sm text-red-400 hover:text-red-300">View calendar</a>
                </div>
            </div>
        </div>
    @endif
</div>
