<div class="relative w-full max-w-md mx-4" x-data="{ isOpen: true }" @click.away="isOpen = false">
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" wire:model.live.debounce.300ms="query" @focus="isOpen = true" @input="isOpen = true"
            class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-lg leading-5 bg-gray-900/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:bg-gray-900 focus:border-red-600 focus:ring-1 focus:ring-red-600 sm:text-sm transition duration-150 ease-in-out"
            placeholder="Search drivers, teams, races..." autocomplete="off">

        <!-- Loading Indicator -->
        <div wire:loading class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <svg class="animate-spin h-4 w-4 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </div>
    </div>

    @if (strlen($query) >= 2 && $isOpen)
        <div
            class="absolute z-50 mt-1 w-full bg-gray-900 border border-gray-800 rounded-lg shadow-xl overflow-hidden backdrop-blur-xl">
            @if (empty($results['drivers']) && empty($results['teams']) && empty($results['races']))
                <div class="p-4 text-center text-gray-500 text-sm">
                    No results found for "<span class="text-white">{{ $query }}</span>"
                </div>
            @else
                <!-- Drivers -->
                @if (isset($results['drivers']) && count($results['drivers']) > 0)
                    <div class="p-2 bg-gray-800/50 text-xs font-bold text-gray-400 uppercase tracking-wider">Drivers
                    </div>
                    <ul>
                        @foreach ($results['drivers'] as $driver)
                            <li>
                                <a href="{{ route('drivers.show', $driver) }}"
                                    class="flex items-center px-4 py-3 hover:bg-gray-800 transition-colors">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center overflow-hidden">
                                        @if ($driver->image_url)
                                            <img src="{{ $driver->image_url }}" alt=""
                                                class="h-full w-full object-cover">
                                        @else
                                            <span
                                                class="text-xs font-bold">{{ substr($driver->first_name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-white">{{ $driver->first_name }}
                                            {{ $driver->last_name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $driver->team->name ?? 'F1 Driver' }}
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <!-- Teams -->
                @if (isset($results['teams']) && count($results['teams']) > 0)
                    <div
                        class="p-2 bg-gray-800/50 text-xs font-bold text-gray-400 uppercase tracking-wider border-t border-gray-800">
                        Teams</div>
                    <ul>
                        @foreach ($results['teams'] as $team)
                            <li>
                                <a href="{{ route('teams.show', $team) }}"
                                    class="flex items-center px-4 py-3 hover:bg-gray-800 transition-colors">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 rounded bg-gray-700 flex items-center justify-center p-1">
                                        @if ($team->logo)
                                            <img src="{{ Str::startsWith($team->logo, 'http') ? $team->logo : asset('storage/' . $team->logo) }}"
                                                class="h-full w-full object-contain">
                                        @else
                                            <span class="text-xs font-bold">{{ substr($team->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-white">{{ $team->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $team->base ?? 'Formula 1 Team' }}</div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <!-- Races -->
                @if (isset($results['races']) && count($results['races']) > 0)
                    <div
                        class="p-2 bg-gray-800/50 text-xs font-bold text-gray-400 uppercase tracking-wider border-t border-gray-800">
                        Races</div>
                    <ul>
                        @foreach ($results['races'] as $race)
                            <li>
                                <a href="#"
                                    class="flex items-center px-4 py-3 hover:bg-gray-800 transition-colors group">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 rounded bg-gray-700 flex items-center justify-center">
                                        <span
                                            class="text-xs font-bold text-gray-400 group-hover:text-white">{{ \Carbon\Carbon::parse($race->race_date)->format('M') }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-white">{{ $race->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($race->race_date)->format('F j, Y') }}</div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif
        </div>
    @endif
</div>
