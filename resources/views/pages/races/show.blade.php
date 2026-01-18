<x-layouts.app :title="$race->name . ' - ' . $race->season" :description="'Complete coverage of the ' .
    $race->name .
    ' at ' .
    ($race->circuit->name ?? 'TBA') .
    '. Race results, standings, and analysis.'" :keywords="$race->name .
    ', F1 race, Grand Prix, ' .
    $race->season .
    ', ' .
    ($race->circuit->country ?? '') .
    ', race results'" :canonical="route('races.show', $race)">

    @push('schema')
        <x-schema.event :race="$race" />
    @endpush
    <!-- Race Header -->
    <section class="relative bg-gradient-to-b from-red-900/30 to-black py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-2 text-gray-400 text-sm mb-4">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span>/</span>
                <a href="{{ route('calendar') }}" class="hover:text-white">Calendar</a>
                <span>/</span>
                <span class="text-white">{{ $race->name }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Race Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-red-500 font-semibold text-sm uppercase tracking-wider">
                            Round {{ $race->round }}
                        </span>
                        @if ($race->isCompleted())
                            <span
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-900/50 text-green-400">
                                Completed
                            </span>
                        @elseif($race->isLive())
                            <span
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-600 text-white animate-pulse">
                                ● Live
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-900/50 text-blue-400">
                                Scheduled
                            </span>
                        @endif
                    </div>

                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        {{ $race->name }}
                    </h1>

                    @if ($race->circuit)
                        <div class="flex items-start gap-3 text-gray-300 mb-6">
                            <svg class="w-5 h-5 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <div class="font-semibold text-lg">{{ $race->circuit->name }}</div>
                                <div class="text-gray-400">{{ $race->circuit->city }}, {{ $race->circuit->country }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-6 text-gray-400">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $race->race_date->format('F j, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $race->race_date->format('H:i') }} UTC</span>
                        </div>
                    </div>
                </div>

                <!-- Race Stats -->
                <div class="bg-gray-900 rounded-lg p-6">
                    <h3 class="text-white font-semibold mb-4">Circuit Information</h3>
                    @if ($race->circuit)
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Length</span>
                                <span class="text-white font-medium">{{ $race->circuit->length }} km</span>
                            </div>
                            @if ($race->laps)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Laps</span>
                                    <span class="text-white font-medium">{{ $race->laps }}</span>
                                </div>
                            @endif
                            @if ($race->distance)
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Race Distance</span>
                                    <span class="text-white font-medium">{{ $race->distance }} km</span>
                                </div>
                            @endif
                            @if ($race->is_sprint_weekend)
                                <div class="flex justify-between">
                                    <span class="text-red-500 font-semibold">Sprint Weekend</span>
                                    <span class="text-red-500">🏁</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Race Results / Sessions -->
    @if ($race->isCompleted() && $race->results->isNotEmpty())
        <!-- Race Results -->
        <section class="py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold text-white mb-6">Race Results</h2>

                <div class="bg-gray-900 rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800 border-b border-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Pos</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Driver</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Team</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Time</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Points</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @foreach ($race->results as $result)
                                    <tr class="hover:bg-gray-800/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-lg font-bold text-white">{{ $result->position }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                @if ($result->driver->image_url)
                                                    <img src="{{ $result->driver->image_url }}"
                                                        alt="{{ $result->driver->name }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @endif
                                                <div>
                                                    <div class="text-white font-medium">{{ $result->driver->name }}
                                                    </div>
                                                    <div class="text-gray-500 text-xs">{{ $result->driver->code }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($result->driver->team)
                                                <div class="flex items-center gap-2">
                                                    @if ($result->driver->team->logo)
                                                        <img src="{{ $result->driver->team->logo }}"
                                                            alt="{{ $result->driver->team->name }}"
                                                            class="w-6 h-6 object-contain">
                                                    @endif
                                                    <span
                                                        class="text-white">{{ $result->driver->team->short_name }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $result->time ?? ($result->status ?: 'N/A') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-red-500 font-bold">{{ $result->points ?? 0 }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Podium Highlight -->
        @php
            $podium = $race->results->whereIn('position', [1, 2, 3])->sortBy('position');
        @endphp
        @if ($podium->count() >= 3)
            <section class="py-12 px-4 bg-gray-900/50">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-2xl font-bold text-white mb-8 text-center">Podium</h2>

                    <div class="flex items-end justify-center gap-4 md:gap-8">
                        <!-- 2nd Place -->
                        <div class="text-center flex-1 max-w-xs">
                            <div class="bg-gradient-to-b from-gray-700 to-gray-800 rounded-t-lg p-6 mb-4">
                                @if ($podium->get(1)->driver->image_url)
                                    <img src="{{ $podium->get(1)->driver->image_url }}"
                                        alt="{{ $podium->get(1)->driver->name }}"
                                        class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-4 border-gray-500">
                                @endif
                                <div class="text-4xl font-bold text-gray-400 mb-2">2</div>
                                <div class="text-white font-semibold text-lg">{{ $podium->get(1)->driver->name }}</div>
                                <div class="text-gray-400 text-sm">{{ $podium->get(1)->driver->team->short_name }}
                                </div>
                            </div>
                            <div class="bg-gray-700 h-32 rounded-b-lg"></div>
                        </div>

                        <!-- 1st Place -->
                        <div class="text-center flex-1 max-w-xs">
                            <div class="bg-gradient-to-b from-yellow-600 to-yellow-700 rounded-t-lg p-6 mb-4">
                                @if ($podium->get(0)->driver->image_url)
                                    <img src="{{ $podium->get(0)->driver->image_url }}"
                                        alt="{{ $podium->get(0)->driver->name }}"
                                        class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-yellow-400">
                                @endif
                                <div class="text-5xl font-bold text-yellow-300 mb-2">1</div>
                                <div class="text-white font-semibold text-xl">{{ $podium->get(0)->driver->name }}
                                </div>
                                <div class="text-yellow-200 text-sm">{{ $podium->get(0)->driver->team->short_name }}
                                </div>
                            </div>
                            <div class="bg-yellow-600 h-48 rounded-b-lg"></div>
                        </div>

                        <!-- 3rd Place -->
                        <div class="text-center flex-1 max-w-xs">
                            <div class="bg-gradient-to-b from-orange-800 to-orange-900 rounded-t-lg p-6 mb-4">
                                @if ($podium->get(2)->driver->image_url)
                                    <img src="{{ $podium->get(2)->driver->image_url }}"
                                        alt="{{ $podium->get(2)->driver->name }}"
                                        class="w-24 h-24 rounded-full mx-auto mb-4 object-cover border-4 border-orange-600">
                                @endif
                                <div class="text-4xl font-bold text-orange-400 mb-2">3</div>
                                <div class="text-white font-semibold text-lg">{{ $podium->get(2)->driver->name }}
                                </div>
                                <div class="text-orange-300 text-sm">{{ $podium->get(2)->driver->team->short_name }}
                                </div>
                            </div>
                            <div class="bg-orange-800 h-24 rounded-b-lg"></div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @elseif($race->isUpcoming())
        <!-- Upcoming Race Countdown -->
        <section class="py-16 px-4 bg-gradient-to-b from-red-900/20 to-black">
            <div class="max-w-5xl mx-auto">
                <!-- Important Notice -->
                <div class="text-center mb-12">
                    <div
                        class="inline-flex items-center gap-2 bg-red-600/20 border border-red-600 rounded-full px-6 py-2 mb-6">
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                        <span class="text-red-400 font-semibold text-sm uppercase tracking-wider">Upcoming Race</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Get Ready for {{ $race->name }}</h2>
                    <p class="text-xl text-gray-300">{{ $race->race_date->format('l, F j, Y') }} at
                        {{ $race->race_date->format('H:i') }} UTC</p>
                </div>

                <div x-data="countdown('{{ $race->race_date->toIso8601String() }}')" class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    <div
                        class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 md:p-8 border border-gray-700 hover:border-red-500 transition-all transform hover:scale-105">
                        <div class="text-5xl md:text-6xl font-bold text-red-500 mb-2" x-text="days">0</div>
                        <div class="text-gray-400 text-sm uppercase font-semibold tracking-wider">Days</div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 md:p-8 border border-gray-700 hover:border-red-500 transition-all transform hover:scale-105">
                        <div class="text-5xl md:text-6xl font-bold text-red-500 mb-2" x-text="hours">0</div>
                        <div class="text-gray-400 text-sm uppercase font-semibold tracking-wider">Hours</div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 md:p-8 border border-gray-700 hover:border-red-500 transition-all transform hover:scale-105">
                        <div class="text-5xl md:text-6xl font-bold text-red-500 mb-2" x-text="minutes">0</div>
                        <div class="text-gray-400 text-sm uppercase font-semibold tracking-wider">Minutes</div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl p-6 md:p-8 border border-gray-700 hover:border-red-500 transition-all transform hover:scale-105">
                        <div class="text-5xl md:text-6xl font-bold text-red-500 mb-2" x-text="seconds">0</div>
                        <div class="text-gray-400 text-sm uppercase font-semibold tracking-wider">Seconds</div>
                    </div>
                </div>

                <!-- Key Information -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-900/50 rounded-lg p-6 border border-gray-800">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-red-600/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold">Location</h3>
                        </div>
                        <p class="text-gray-300">{{ $race->circuit->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $race->circuit->city }}, {{ $race->circuit->country }}
                        </p>
                    </div>

                    <div class="bg-gray-900/50 rounded-lg p-6 border border-gray-800">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-red-600/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold">Circuit Details</h3>
                        </div>
                        <p class="text-gray-300">{{ $race->circuit->length }} km</p>
                        <p class="text-gray-500 text-sm">{{ $race->laps ?? 'TBA' }} Laps</p>
                    </div>

                    <div class="bg-gray-900/50 rounded-lg p-6 border border-gray-800">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-red-600/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-white font-semibold">Schedule</h3>
                        </div>
                        <p class="text-gray-300">Round {{ $race->round }} of {{ $race->season }}</p>
                        @if ($race->is_sprint_weekend)
                            <p class="text-red-500 text-sm font-semibold">🏁 Sprint Weekend</p>
                        @else
                            <p class="text-gray-500 text-sm">Traditional Format</p>
                        @endif
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="mt-8 text-center">
                    <p class="text-gray-400 mb-4">Don't miss the action!</p>
                    <div class="flex gap-4 justify-center flex-wrap">
                        <a href="{{ route('calendar') }}"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            View Full Calendar
                        </a>
                        <a href="{{ route('drivers.index') }}"
                            class="bg-gray-800 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            View Drivers
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Circuit Image -->
    @if ($race->circuit && $race->circuit->image)
        <section class="py-12 px-4">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold text-white mb-6">Circuit Layout</h2>
                <div class="bg-gray-900 rounded-lg p-4">
                    <img src="{{ $race->circuit->image }}" alt="{{ $race->circuit->name }}"
                        class="w-full h-auto rounded-lg">
                </div>
            </div>
        </section>
    @endif

    <!-- Related News -->
    @if ($race->news && $race->news->isNotEmpty())
        <section class="py-12 px-4 bg-gray-900/50">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold text-white mb-6">Related News</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($race->news as $newsItem)
                        <a href="{{ route('news.show', $newsItem) }}"
                            class="bg-gray-900 rounded-lg overflow-hidden hover:shadow-xl hover:shadow-red-500/20 transition-all">
                            @if ($newsItem->featured_image)
                                <img src="{{ $newsItem->featured_image }}" alt="{{ $newsItem->title }}"
                                    class="w-full h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <h3 class="text-white font-semibold mb-2 line-clamp-2">{{ $newsItem->title }}</h3>
                                <p class="text-gray-400 text-sm line-clamp-2">{{ $newsItem->excerpt }}</p>
                                <div class="mt-3 text-xs text-gray-500">
                                    {{ $newsItem->published_at->diffForHumans() }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
