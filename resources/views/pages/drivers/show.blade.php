<x-layouts.app :title="$driver->name . ' - F1 Driver Profile'" :description="$driver->name .
    ' driver profile. ' .
    ($driver->team ? 'Racing for ' . $driver->team->name . '.' : '') .
    ' View stats, race results, and career highlights.'" :keywords="$driver->name .
    ', F1 driver, ' .
    ($driver->nationality ?? '') .
    ', ' .
    ($driver->team ? $driver->team->name : '') .
    ', Formula 1'" :canonical="route('drivers.show', $driver)">

    @push('schema')
        <x-schema.person :driver="$driver" />
    @endpush

    <!-- Driver Header -->
    <section class="relative bg-gradient-to-b from-red-900/30 to-black py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-gray-400 text-sm mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('drivers.index') }}" class="hover:text-white transition-colors">Drivers</a>
                <span>/</span>
                <span class="text-white">{{ $driver->name }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Driver Image & Basic Info -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-900 rounded-xl overflow-hidden">
                        @if ($driver->image_url)
                            <img src="{{ $driver->image_url }}" alt="{{ $driver->name }}"
                                class="w-full h-64 object-cover object-top">
                        @else
                            <div
                                class="w-full h-64 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                <span class="text-6xl font-bold text-gray-700">
                                    {{ substr($driver->first_name ?? $driver->name, 0, 1) }}{{ substr($driver->last_name ?? '', 0, 1) }}
                                </span>
                            </div>
                        @endif

                        <div class="p-6">
                            @if ($driver->driver_number)
                                <div class="flex items-center gap-4 mb-4">
                                    <span class="text-5xl font-bold text-red-500">{{ $driver->driver_number }}</span>
                                    @if ($driver->code)
                                        <span class="text-2xl font-semibold text-gray-400">{{ $driver->code }}</span>
                                    @endif
                                </div>
                            @endif

                            @if ($driver->team)
                                <a href="{{ route('teams.show', $driver->team) }}"
                                    class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                                    @if ($driver->team->logo)
                                        <img src="{{ asset('storage/' . $driver->team->logo) }}"
                                            alt="{{ $driver->team->name }}" class="w-10 h-10 object-contain">
                                    @endif
                                    <div>
                                        <div class="text-xs text-gray-400 uppercase">Team</div>
                                        <div class="text-white font-medium">{{ $driver->team->name }}</div>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Driver Details -->
                <div class="lg:col-span-2">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-2">
                        {{ $driver->name }}
                    </h1>

                    @if ($driver->nationality)
                        <div class="flex items-center gap-2 text-gray-400 mb-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                            </svg>
                            <span>{{ $driver->nationality }}</span>
                        </div>
                    @endif

                    <!-- Quick Stats Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                            <div class="text-gray-400 text-sm mb-1">Championships</div>
                            <div class="text-2xl font-bold text-white">{{ $stats['championships'] }}</div>
                        </div>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                            <div class="text-gray-400 text-sm mb-1">Race Wins</div>
                            <div class="text-2xl font-bold text-white">{{ $stats['race_wins'] }}</div>
                        </div>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                            <div class="text-gray-400 text-sm mb-1">Podiums</div>
                            <div class="text-2xl font-bold text-white">{{ $stats['podiums'] }}</div>
                        </div>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                            <div class="text-gray-400 text-sm mb-1">Pole Positions</div>
                            <div class="text-2xl font-bold text-white">{{ $stats['pole_positions'] }}</div>
                        </div>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                            <div class="text-gray-400 text-sm mb-1">Fastest Laps</div>
                            <div class="text-2xl font-bold text-white">{{ $stats['fastest_laps'] }}</div>
                        </div>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-800">
                            <div class="text-gray-400 text-sm mb-1">Career Points</div>
                            <div class="text-2xl font-bold text-white">{{ number_format($stats['career_points']) }}
                            </div>
                        </div>
                    </div>

                    <!-- Recent Form -->
                    @if ($recentForm->isNotEmpty())
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-white mb-3">Recent Form</h3>
                            <div class="flex gap-2">
                                @foreach ($recentForm as $position)
                                    <div
                                        class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-bold
                                        @if ($position === 1) bg-yellow-500 text-black
                                        @elseif($position === 2) bg-gray-400 text-black
                                        @elseif($position === 3) bg-amber-700 text-white
                                        @elseif($position === 'DNF') bg-red-900 text-red-400
                                        @elseif(is_numeric($position) && $position <= 10) bg-green-900/50 text-green-400
                                        @else bg-gray-800 text-gray-400 @endif">
                                        {{ $position === 'DNF' ? 'DNF' : 'P' . $position }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Driver Bio -->
                    @if ($driver->bio)
                        <div class="prose prose-invert max-w-none">
                            <h3 class="text-lg font-semibold text-white mb-3">Biography</h3>
                            <p class="text-gray-400">{{ $driver->bio }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Season Results -->
    @if ($driver->raceResults && $driver->raceResults->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 py-12">
            <h2 class="text-2xl font-bold text-white mb-6">{{ date('Y') }} Season Results</h2>

            <div class="bg-gray-900 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Race</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Grid</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Position</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Points</th>
                                <th
                                    class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach ($driver->raceResults as $result)
                                <tr class="hover:bg-gray-800/50 transition-colors">
                                    <td class="px-4 py-4">
                                        <a href="{{ route('races.show', $result->race) }}"
                                            class="text-white hover:text-red-400 transition-colors font-medium">
                                            {{ $result->race->name }}
                                        </a>
                                        <div class="text-xs text-gray-500">
                                            {{ $result->race->race_date->format('M j, Y') }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-400">
                                        {{ $result->grid ?? '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if ($result->position)
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold
                                                @if ($result->position === 1) bg-yellow-500 text-black
                                                @elseif($result->position === 2) bg-gray-400 text-black
                                                @elseif($result->position === 3) bg-amber-700 text-white
                                                @elseif($result->position <= 10) bg-green-900/50 text-green-400
                                                @else bg-gray-800 text-gray-400 @endif">
                                                {{ $result->position }}
                                            </span>
                                        @else
                                            <span class="text-red-400">DNF</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="text-white font-medium">{{ $result->points ?? 0 }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if ($result->status && $result->status !== 'Finished')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-900/50 text-red-400">
                                                {{ $result->status }}
                                            </span>
                                        @else
                                            <span class="text-green-400">✓</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    @endif

    <!-- Related News -->
    @if ($driver->articles && $driver->articles->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 py-12 border-t border-gray-800">
            <h2 class="text-2xl font-bold text-white mb-6">Latest News</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($driver->articles->take(3) as $article)
                    <a href="{{ route('news.show', $article) }}"
                        class="group bg-gray-900 rounded-xl overflow-hidden hover:bg-gray-800 transition-colors">
                        @if ($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}"
                                class="w-full h-40 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="text-white font-medium group-hover:text-red-400 transition-colors line-clamp-2">
                                {{ $article->title }}
                            </h3>
                            <p class="text-gray-500 text-sm mt-2">
                                {{ $article->published_at?->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Team Info Card -->
    @if ($driver->team)
        <section class="max-w-7xl mx-auto px-4 py-12 border-t border-gray-800">
            <h2 class="text-2xl font-bold text-white mb-6">Current Team</h2>

            <a href="{{ route('teams.show', $driver->team) }}"
                class="block bg-gray-900 rounded-xl p-6 hover:bg-gray-800 transition-colors">
                <div class="flex items-center gap-6">
                    @if ($driver->team->logo)
                        <img src="{{ asset('storage/' . $driver->team->logo) }}" alt="{{ $driver->team->name }}"
                            class="w-24 h-24 object-contain">
                    @endif
                    <div>
                        <h3 class="text-2xl font-bold text-white">{{ $driver->team->name }}</h3>
                        @if ($driver->team->nationality)
                            <p class="text-gray-400">{{ $driver->team->nationality }}</p>
                        @endif
                        @if ($driver->team->drivers && $driver->team->drivers->count() > 1)
                            <div class="mt-3">
                                <span class="text-gray-500 text-sm">Teammates:</span>
                                @foreach ($driver->team->drivers->where('id', '!=', $driver->id) as $teammate)
                                    <a href="{{ route('drivers.show', $teammate) }}"
                                        class="text-red-400 hover:text-red-300 text-sm ml-1">
                                        {{ $teammate->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        </section>
    @endif
</x-layouts.app>
