<x-layouts.app title="F1 Teams - 2026 Season"
    description="All Formula 1 constructor teams competing in the 2026 season. View team profiles, driver lineups, standings, and statistics."
    keywords="F1 teams, Formula 1 teams 2026, constructor standings, F1 constructors, team profiles" :canonical="route('teams.index')">

    @push('schema')
        <x-schema.teams-list :teams="$teams" />
    @endpush
    <!-- Header Section -->
    <section class="relative bg-gradient-to-b from-red-900/30 to-black py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                F1 Teams <span class="text-red-500">2026</span>
            </h1>
            <p class="text-xl text-gray-400">
                All constructor teams competing in the 2026 Formula 1 World Championship
            </p>
        </div>
    </section>

    <!-- Teams Grid -->
    <section class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            @if ($teams->isEmpty())
                <div class="text-center py-12">
                    <p class="text-gray-400 text-lg">No teams found. Please seed the database.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($teams as $team)
                        <div class="bg-gray-900 rounded-lg overflow-hidden hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300 border-l-4"
                            style="border-color: {{ $team->color ?? '#ef4444' }};">

                            <!-- Team Header -->
                            <div class="bg-gradient-to-r from-gray-800 to-gray-900 p-6"
                                style="background: linear-gradient(to right, {{ $team->color }}15, #111827);">
                                <div class="flex items-center gap-4">
                                    @if ($team->logo)
                                        <div class="w-20 h-20 bg-white rounded-lg p-3 flex items-center justify-center">
                                            <img src="{{ $team->logo }}" alt="{{ $team->name }}"
                                                class="w-full h-full object-contain">
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <h3 class="text-2xl font-bold text-white mb-1">{{ $team->name }}</h3>
                                        <p class="text-gray-400 text-sm">{{ $team->base ?? 'Unknown Base' }}</p>
                                    </div>

                                    @if ($team->world_championships > 0)
                                        <div class="text-center">
                                            <div class="text-3xl font-bold"
                                                style="color: {{ $team->color ?? '#ef4444' }};">
                                                {{ $team->world_championships }}
                                            </div>
                                            <div class="text-xs text-gray-500 uppercase">Titles</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                <!-- Team Info -->
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    @if ($team->team_principal)
                                        <div>
                                            <div class="text-gray-500 text-xs uppercase mb-1">Team Principal</div>
                                            <div class="text-white font-medium">{{ $team->team_principal }}</div>
                                        </div>
                                    @endif
                                    @if ($team->power_unit)
                                        <div>
                                            <div class="text-gray-500 text-xs uppercase mb-1">Power Unit</div>
                                            <div class="text-white font-medium">{{ $team->power_unit }}</div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Current Standing -->
                                @if ($team->constructorStandings->isNotEmpty())
                                    @php
                                        $standing = $team->constructorStandings->first();
                                    @endphp
                                    <div class="bg-gray-800/50 rounded-lg p-4 mb-6">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-gray-400 text-sm">Current Position</span>
                                            <span
                                                class="text-2xl font-bold text-white">P{{ $standing->position }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-400 text-sm">Points</span>
                                            <span class="text-xl font-bold"
                                                style="color: {{ $team->color ?? '#ef4444' }};">
                                                {{ $standing->points }}
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Drivers -->
                                @if ($team->drivers->isNotEmpty())
                                    <div class="mb-6">
                                        <h4 class="text-gray-400 text-xs uppercase mb-3 font-semibold">Drivers</h4>
                                        <div class="space-y-3">
                                            @foreach ($team->drivers as $driver)
                                                <a href="{{ route('drivers.show', $driver) }}"
                                                    class="flex items-center gap-3 p-3 bg-gray-800/30 rounded-lg hover:bg-gray-800 transition-colors group">
                                                    @if ($driver->image_url)
                                                        <img src="{{ $driver->image_url }}" alt="{{ $driver->name }}"
                                                            class="w-12 h-12 rounded-full object-cover border-2"
                                                            style="border-color: {{ $team->color ?? '#ef4444' }};">
                                                    @else
                                                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg"
                                                            style="background-color: {{ $team->color ?? '#ef4444' }};">
                                                            {{ $driver->code }}
                                                        </div>
                                                    @endif
                                                    <div class="flex-1">
                                                        <div
                                                            class="text-white font-medium group-hover:text-red-400 transition-colors">
                                                            {{ $driver->name }}
                                                        </div>
                                                        <div class="text-gray-500 text-xs flex items-center gap-2">
                                                            <span
                                                                class="fi fi-{{ strtolower($driver->country_code) }}"></span>
                                                            <span>#{{ $driver->number }}</span>
                                                        </div>
                                                    </div>
                                                    @if ($driver->standings->isNotEmpty())
                                                        <div class="text-right">
                                                            <div class="text-white font-bold">
                                                                P{{ $driver->standings->first()->position }}</div>
                                                            <div class="text-gray-500 text-xs">
                                                                {{ $driver->standings->first()->points }} pts</div>
                                                        </div>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- View Team Details Button -->
                                <a href="{{ route('teams.show', $team) }}"
                                    class="block w-full text-center font-medium py-3 px-4 rounded-lg transition-colors"
                                    style="background-color: {{ $team->color ?? '#ef4444' }}20; color: {{ $team->color ?? '#ef4444' }}; border: 1px solid {{ $team->color ?? '#ef4444' }}40;">
                                    View Team Details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Constructor Standings Summary -->
    @if ($teams->isNotEmpty())
        <section class="py-12 px-4 bg-gray-900/50">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-2xl font-bold text-white mb-6">Constructor Standings 2026</h2>

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
                                        Team</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Base</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Power Unit</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Points</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @php
                                    $sortedTeams = $teams->sortBy(function ($team) {
                                        return $team->constructorStandings->first()?->position ?? 999;
                                    });
                                @endphp
                                @foreach ($sortedTeams as $team)
                                    @if ($team->constructorStandings->isNotEmpty())
                                        @php
                                            $standing = $team->constructorStandings->first();
                                        @endphp
                                        <tr class="hover:bg-gray-800/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-lg font-bold text-white">{{ $standing->position }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    @if ($team->logo)
                                                        <div
                                                            class="w-10 h-10 bg-white rounded p-1.5 flex items-center justify-center">
                                                            <img src="{{ $team->logo }}" alt="{{ $team->name }}"
                                                                class="w-full h-full object-contain">
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="text-white font-medium">{{ $team->name }}</div>
                                                        <div class="w-16 h-1 rounded-full mt-1"
                                                            style="background-color: {{ $team->color ?? '#ef4444' }};">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                                                {{ $team->base ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                                                {{ $team->power_unit ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-2xl font-bold"
                                                    style="color: {{ $team->color ?? '#ef4444' }};">
                                                    {{ $standing->points }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
