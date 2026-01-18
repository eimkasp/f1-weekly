<div class="standings-container">
    <div class="standings-header">
        <h2 class="text-2xl font-bold text-white">Driver Standings {{ $season }}</h2>
    </div>

    <div class="standings-table mt-4">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-700">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pos
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Driver
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Team
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Points</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Wins
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($standings as $standing)
                        <tr class="hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                    @if ($standing->position === 1) bg-yellow-500 text-black
                                    @elseif($standing->position === 2) bg-gray-400 text-black
                                    @elseif($standing->position === 3) bg-amber-700 text-white
                                    @else bg-gray-700 text-white @endif font-bold text-sm">
                                    {{ $standing->position }}
                                </span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($standing->driver->image_url)
                                        <img class="h-10 w-10 rounded-full object-cover mr-3"
                                            src="{{ $standing->driver->image_url }}"
                                            alt="{{ $standing->driver->name }}">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-gray-700 mr-3 flex items-center justify-center">
                                            <span class="text-gray-400 text-sm">{{ $standing->driver->number }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('drivers.show', $standing->driver) }}"
                                            class="text-white font-medium hover:text-red-500 transition-colors">
                                            {{ $standing->driver->name }}
                                        </a>
                                        <div class="text-gray-500 text-sm">#{{ $standing->driver->number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if ($standing->driver->team)
                                    <div class="flex items-center">
                                        <div class="w-1 h-6 rounded mr-2"
                                            style="background-color: {{ $standing->driver->team->color ?? '#666' }}">
                                        </div>
                                        <span class="text-gray-300">{{ $standing->driver->team->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <span class="text-white font-bold text-lg">{{ $standing->points }}</span>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <span class="text-gray-400">{{ $standing->wins }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                No standings data available for {{ $season }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
