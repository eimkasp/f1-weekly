<div class="polymarket-odds">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-white">Prediction Markets</h3>
                <p class="text-xs text-gray-500">Powered by Polymarket</p>
            </div>
        </div>

        <a href="https://polymarket.com" target="_blank" rel="noopener"
            class="text-xs text-gray-500 hover:text-gray-400 flex items-center gap-1">
            View on Polymarket
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
        </a>
    </div>

    <!-- View Tabs -->
    <div class="flex gap-2 mb-4 overflow-x-auto pb-2">
        <button wire:click="setView('championship')"
            class="px-3 py-1.5 rounded-lg text-sm font-medium whitespace-nowrap transition-colors
                {{ $view === 'championship' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            Championship
        </button>
        <button wire:click="setView('race')"
            class="px-3 py-1.5 rounded-lg text-sm font-medium whitespace-nowrap transition-colors
                {{ $view === 'race' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            Race Winners
        </button>
        <button wire:click="setView('all')"
            class="px-3 py-1.5 rounded-lg text-sm font-medium whitespace-nowrap transition-colors
                {{ $view === 'all' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            All Markets
        </button>
    </div>

    <!-- Markets List -->
    @if ($markets->isEmpty())
        <div class="text-center py-8 bg-gray-900/50 rounded-xl border border-gray-800">
            <div class="w-12 h-12 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <p class="text-gray-400 text-sm mb-2">No F1 markets available</p>
            <p class="text-gray-600 text-xs">Markets will appear when Polymarket lists F1 predictions</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($markets as $market)
                <div class="bg-gray-900 rounded-lg p-4 border border-gray-800 hover:border-gray-700 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-white font-medium text-sm leading-tight mb-2 line-clamp-2">
                                {{ $market->title }}
                            </h4>

                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                @if ($market->driver)
                                    <span class="px-2 py-0.5 bg-gray-800 text-gray-400 rounded">
                                        {{ $market->driver->name }}
                                    </span>
                                @endif
                                @if ($market->race)
                                    <span class="px-2 py-0.5 bg-gray-800 text-gray-400 rounded">
                                        {{ $market->race->name }}
                                    </span>
                                @endif
                                <span class="text-gray-600">
                                    Vol: {{ $market->formatted_volume }}
                                </span>
                            </div>
                        </div>

                        <!-- Odds Display -->
                        <div class="flex gap-2 flex-shrink-0">
                            @if ($market->price_yes)
                                <div class="text-center">
                                    <div class="px-3 py-2 bg-green-900/30 border border-green-800/50 rounded-lg">
                                        <div class="text-green-400 font-bold text-lg">
                                            {{ $market->implied_probability_yes }}%
                                        </div>
                                        <div class="text-green-600 text-xs uppercase font-medium">Yes</div>
                                    </div>
                                </div>
                            @endif
                            @if ($market->price_no)
                                <div class="text-center">
                                    <div class="px-3 py-2 bg-red-900/30 border border-red-800/50 rounded-lg">
                                        <div class="text-red-400 font-bold text-lg">
                                            {{ $market->implied_probability_no }}%
                                        </div>
                                        <div class="text-red-600 text-xs uppercase font-medium">No</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Progress bar showing Yes probability -->
                    @if ($market->price_yes)
                        <div class="mt-3 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-green-400 rounded-full transition-all duration-500"
                                style="width: {{ $market->implied_probability_yes }}%"></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if (!$showAll && $markets->count() >= 10)
            <button wire:click="$set('showAll', true)"
                class="w-full mt-4 py-2 text-sm text-gray-400 hover:text-white bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors">
                Show All Markets
            </button>
        @endif
    @endif

    <!-- Disclaimer -->
    <p class="text-xs text-gray-600 mt-4 text-center">
        Odds reflect market sentiment, not guaranteed outcomes. Trade responsibly.
    </p>
</div>
