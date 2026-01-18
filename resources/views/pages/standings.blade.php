<x-layouts.app title="Championship Standings - F1 Weekly"
    description="Live F1 Championship Standings 2026. Track driver and constructor positions, points, wins, and race-by-race updates."
    keywords="F1 standings, Formula 1 championship, driver standings, constructor standings, F1 points 2026"
    :canonical="route('standings')">

    @push('schema')
        <x-schema.standings />
    @endpush

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors">
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-600 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-white">Standings</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">{{ now()->year }} Championship Standings</h1>
            <p class="text-gray-400">Current driver and constructor championship positions</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex space-x-4 mb-8">
            <a href="{{ route('standings') }}"
                class="px-4 py-2 rounded-lg font-medium {{ !request()->is('standings/constructors') ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                Drivers
            </a>
            <a href="{{ route('standings.constructors') }}"
                class="px-4 py-2 rounded-lg font-medium {{ request()->is('standings/constructors') ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                Constructors
            </a>
        </div>

        <livewire:driver-standings :limit="20" />
    </div>
</x-layouts.app>
