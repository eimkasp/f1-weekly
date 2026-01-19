<x-layouts.app :title="$team->name . ' - F1 Team Profile'" :description="$team->name . ' Formula 1 team profile. History, stats, drivers, and latest news.'" :keywords="$team->name . ', F1 team, Formula 1, ' . $team->base" :canonical="route('teams.show', $team)">

    <!-- Team Header -->
    <section class="relative bg-black py-12 px-4 border-b border-gray-800"
        style="
        @if ($team->color) border-bottom-color: {{ $team->color }}; box-shadow: 0 4px 20px -10px {{ $team->color }}; @endif
    ">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-2 text-gray-400 text-sm mb-8">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                <span>/</span>
                <span class="text-white">Teams</span>
                <span>/</span>
                <span class="text-white">{{ $team->name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Logo -->
                <div class="w-32 h-32 md:w-48 md:h-48 bg-white/5 rounded-2xl flex items-center justify-center p-4">
                    @if ($team->logo)
                        <img src="{{ Str::startsWith($team->logo, 'http') ? $team->logo : asset('storage/' . $team->logo) }}"
                            alt="{{ $team->name }}" class="w-full h-full object-contain">
                    @else
                        <span class="text-4xl font-bold text-gray-700">{{ substr($team->name, 0, 2) }}</span>
                    @endif
                </div>

                <!-- Info -->
                <div class="flex-1 text-center md:text-left space-y-4">
                    <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight">
                        {{ $team->name }}
                    </h1>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 max-w-3xl">
                        <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-800">
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Base</div>
                            <div class="font-medium text-white">{{ $team->base ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-800">
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Team Principal
                            </div>
                            <div class="font-medium text-white">{{ $team->team_principal ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-800">
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Power Unit</div>
                            <div class="font-medium text-white">{{ $team->power_unit ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-800">
                            <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">First Entry</div>
                            <div class="font-medium text-white">{{ $team->founded ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats & Drivers -->
    <section class="py-12 px-4">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-3 gap-8">

            <!-- Sidebar / Stats -->
            <div class="space-y-8">
                <!-- World Championships -->
                <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl p-6 border border-gray-800">
                    <h3 class="text-gray-400 font-bold uppercase tracking-widest text-sm mb-4">World Championships</h3>
                    <div class="flex items-center gap-4">
                        <span class="text-6xl font-black text-white">{{ $team->world_championships }}</span>
                        <div class="space-y-1">
                            @if ($team->world_championships > 0)
                                <div class="flex gap-1">
                                    @for ($i = 0; $i < min($team->world_championships, 5); $i++)
                                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    @if ($team->world_championships > 5)
                                        <span
                                            class="text-yellow-500 font-bold">+{{ $team->world_championships - 5 }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="text-sm text-gray-500">Constructors' Titles</div>
                        </div>
                    </div>
                </div>

                <!-- Current Drivers -->
                <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800">
                    <div class="p-4 border-b border-gray-800 bg-gray-800/50">
                        <h3 class="font-bold text-white">Current Drivers</h3>
                    </div>
                    <div class="divide-y divide-gray-800">
                        @foreach ($team->drivers as $driver)
                            <a href="{{ route('drivers.show', $driver) }}"
                                class="flex items-center gap-4 p-4 hover:bg-gray-800 transition-colors group">
                                <div
                                    class="w-12 h-12 rounded-full overflow-hidden bg-gray-800 border-2 border-transparent group-hover:border-red-600 transition-colors">
                                    @if ($driver->image_url)
                                        <img src="{{ $driver->image_url }}" class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-gray-500 font-bold">
                                            {{ substr($driver->first_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="text-white font-bold group-hover:text-red-500 transition-colors">
                                        {{ $driver->first_name }} {{ $driver->last_name }}</div>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-2xl font-black text-gray-600 group-hover:text-white transition-colors">{{ $driver->number }}</span>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-600 group-hover:text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Bio -->
                @if ($team->description)
                    <div class="prose prose-invert max-w-none">
                        <h3 class="text-2xl font-bold text-white mb-4">Team History</h3>
                        <div class="text-gray-300 leading-relaxed space-y-4">
                            {!! nl2br(e($team->description)) !!}
                        </div>
                    </div>
                @endif

                <!-- Recent Results -->
                @if ($team->raceResults && $team->raceResults->count() > 0)
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-6">Recent Results</h3>
                        <div class="bg-gray-900 rounded-xl overflow-hidden border border-gray-800">
                            <table class="w-full text-left">
                                <thead class="bg-gray-800 text-xs uppercase text-gray-400 font-bold">
                                    <tr>
                                        <th class="px-6 py-4">Race</th>
                                        <th class="px-6 py-4">Driver</th>
                                        <th class="px-6 py-4 text-center">Pos</th>
                                        <th class="px-6 py-4 text-right">Pts</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800">
                                    @foreach ($team->raceResults as $result)
                                        <tr class="hover:bg-gray-800/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-white">{{ $result->race->name }}</div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $result->race->race_date ? \Carbon\Carbon::parse($result->race->race_date)->format('M d') : '' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-gray-300">
                                                {{ $result->driver->first_name }}
                                                {{ substr($result->driver->last_name, 0, 1) }}.
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    class="inline-block px-2 py-1 rounded font-bold text-xs
                                                   {{ $result->position == 1
                                                       ? 'bg-yellow-500/20 text-yellow-500'
                                                       : ($result->position <= 3
                                                           ? 'bg-gray-500/20 text-gray-300'
                                                           : ($result->position <= 10
                                                               ? 'bg-green-500/20 text-green-500'
                                                               : 'text-gray-500')) }}">
                                                    {{ $result->positionText ?? $result->position }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right font-mono text-white">
                                                {{ $result->points > 0 ? $result->points : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
