<x-layouts.app title="F1 Drivers - 2026 Season"
    description="Complete list of all Formula 1 drivers competing in the 2026 season. View driver profiles, statistics, teams, and championship standings."
    keywords="F1 drivers, Formula 1 drivers 2026, driver standings, F1 teams, driver statistics" :canonical="route('drivers.index')">

    @push('schema')
        <x-schema.drivers-list :drivers="$drivers" />
    @endpush
    <!-- Header Section -->
    <section class="relative bg-gradient-to-b from-red-900/30 to-black py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                F1 Drivers <span class="text-red-500">2026</span>
            </h1>
            <p class="text-xl text-gray-400">
                All drivers competing in the 2026 Formula 1 World Championship
            </p>
        </div>
    </section>

    <!-- Drivers Grid -->
    <section class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            @if ($drivers->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-400 text-lg">No drivers found. Please seed the database.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($drivers as $driver)
                        <div
                            class="bg-gray-900 rounded-lg overflow-hidden hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300">
                            <!-- Driver Image -->
                            <div class="relative h-64 bg-gradient-to-b from-gray-800 to-gray-900"
                                style="background-color: {{ $driver->team->color ?? '#1f2937' }}33;">
                                @if ($driver->image_url)
                                    <img src="{{ $driver->image_url }}" alt="{{ $driver->name }}"
                                        class="w-full h-full object-cover object-top">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <span
                                            class="text-6xl font-bold text-gray-700">{{ $driver->code ?? strtoupper(substr($driver->first_name, 0, 3)) }}</span>
                                    </div>
                                @endif

                                <!-- Driver Number Badge -->
                                <div
                                    class="absolute top-4 right-4 bg-white text-black font-bold text-2xl w-12 h-12 rounded-full flex items-center justify-center shadow-lg">
                                    {{ $driver->number }}
                                </div>
                            </div>

                            <!-- Driver Info -->
                            <div class="p-5">
                                <div class="mb-3">
                                    <h3 class="text-2xl font-bold text-white mb-1">{{ $driver->name }}</h3>
                                    <div class="flex items-center gap-2 text-sm text-gray-400">
                                        <span class="fi fi-{{ strtolower($driver->country_code) }} text-lg"></span>
                                        <span>{{ $driver->nationality }}</span>
                                    </div>
                                </div>

                                <!-- Team Info -->
                                @if ($driver->team)
                                    <div class="flex items-center gap-3 mb-4 p-3 bg-gray-800/50 rounded-lg"
                                        style="border-left: 3px solid {{ $driver->team->color ?? '#ef4444' }};">
                                        @if ($driver->team->logo)
                                            <img src="{{ $driver->team->logo }}" alt="{{ $driver->team->name }}"
                                                class="w-8 h-8 object-contain">
                                        @endif
                                        <span class="text-sm font-medium text-white">{{ $driver->team->name }}</span>
                                    </div>
                                @endif

                                <!-- Stats -->
                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-red-500">{{ $driver->championships ?? 0 }}
                                        </div>
                                        <div class="text-xs text-gray-500">Titles</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-red-500">{{ $driver->race_wins ?? 0 }}
                                        </div>
                                        <div class="text-xs text-gray-500">Wins</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-red-500">{{ $driver->podiums ?? 0 }}</div>
                                        <div class="text-xs text-gray-500">Podiums</div>
                                    </div>
                                </div>

                                <!-- Current Standing (if available) -->
                                @if ($driver->standings->isNotEmpty())
                                    <div class="bg-gray-800 rounded-lg p-3 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-400 text-sm">Current Position</span>
                                            <span
                                                class="text-xl font-bold text-white">P{{ $driver->standings->first()->position }}</span>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-gray-400 text-sm">Points</span>
                                            <span
                                                class="text-lg font-bold text-red-500">{{ $driver->standings->first()->points }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- View Profile Button -->
                                <a href="{{ route('drivers.show', $driver) }}"
                                    class="block w-full bg-red-600 hover:bg-red-700 text-white text-center font-medium py-2 px-4 rounded-lg transition-colors">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
