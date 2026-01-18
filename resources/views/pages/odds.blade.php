<x-layouts.app title="F1 Prediction Markets - Odds & Betting"
    description="View real-time F1 prediction market odds from Polymarket. Championship odds, race winners, and more.">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-2">/</span>
                <span class="text-white">Prediction Markets</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">F1 Prediction Markets</h1>
                    <p class="text-gray-400">Real-time odds from decentralized prediction markets</p>
                </div>

                <a href="https://polymarket.com" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg transition-all self-start">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Trade on Polymarket
                </a>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="bg-gradient-to-r from-purple-900/30 to-blue-900/30 border border-purple-600/30 rounded-xl p-4 mb-8">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-purple-600/30 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-sm">
                    <p class="text-purple-200 font-medium mb-1">About Prediction Markets</p>
                    <p class="text-gray-400">
                        Prediction markets aggregate collective knowledge to forecast outcomes. Prices reflect
                        market-implied probabilities—a 75% price means the market believes there's a 75% chance of that
                        outcome.
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Markets Column -->
            <div class="lg:col-span-2">
                <livewire:polymarket-odds />
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800">
                    <h3 class="text-lg font-bold text-white mb-4">How It Works</h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 bg-green-900/50 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-green-400 text-xs font-bold">1</span>
                            </div>
                            <div>
                                <p class="text-white font-medium">Choose a Market</p>
                                <p class="text-gray-500">Browse F1 prediction markets for championships, races, and
                                    more.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 bg-green-900/50 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-green-400 text-xs font-bold">2</span>
                            </div>
                            <div>
                                <p class="text-white font-medium">Buy Yes or No</p>
                                <p class="text-gray-500">Purchase shares based on your prediction. Prices are between
                                    $0.01 - $0.99.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div
                                class="w-6 h-6 bg-green-900/50 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-green-400 text-xs font-bold">3</span>
                            </div>
                            <div>
                                <p class="text-white font-medium">Win $1 Per Share</p>
                                <p class="text-gray-500">If you're correct, each share pays out $1. If wrong, you lose
                                    your stake.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Understanding Odds -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800">
                    <h3 class="text-lg font-bold text-white mb-4">Reading the Odds</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                            <span class="text-gray-400">High probability</span>
                            <span class="text-green-400 font-bold">70-99%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                            <span class="text-gray-400">Moderate</span>
                            <span class="text-yellow-400 font-bold">30-70%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                            <span class="text-gray-400">Low probability</span>
                            <span class="text-red-400 font-bold">1-30%</span>
                        </div>
                    </div>

                    <p class="text-gray-500 text-xs mt-4">
                        Remember: The market price reflects crowd wisdom, but upsets happen. Always trade responsibly.
                    </p>
                </div>

                <!-- Disclaimer -->
                <div class="bg-yellow-900/20 border border-yellow-600/30 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="text-sm">
                            <p class="text-yellow-400 font-medium mb-1">Disclaimer</p>
                            <p class="text-yellow-200/70 text-xs">
                                Prediction markets involve risk. This is not financial advice. Only trade what you can
                                afford to lose. Polymarket may not be available in all jurisdictions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
