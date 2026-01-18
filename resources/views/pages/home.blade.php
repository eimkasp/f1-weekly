<x-layouts.app title="F1 Weekly - AI-Powered F1 News">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-b from-red-900/30 to-black py-16 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                    Your AI-Powered <span class="text-red-500">F1</span> News Source
                </h1>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto mb-8">
                    Stay ahead of the grid with intelligent analysis, real-time updates, and comprehensive coverage of
                    Formula 1.
                </p>

                <div class="flex flex-wrap justify-center gap-4 mb-4">
                    <a href="{{ route('news.index') }}"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-red-900/20">
                        Latest News
                    </a>
                    <a href="{{ route('standings') }}"
                        class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-xl transition-colors border border-gray-700 shadow-lg">
                        Standings
                    </a>
                </div>
            </div>

            <!-- Live Race Tracker (if live) -->
            <div class="max-w-4xl mx-auto space-y-8">
                <livewire:live-race-tracker />
                <livewire:next-race-countdown />
            </div>
        </div>
    </section>

    <!-- F1 Arcade Promo -->
    <section class="py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-900 to-black shadow-2xl border border-red-900/50">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-600 rounded-full blur-3xl opacity-20">
                </div>
                <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-center md:text-left">
                        <span
                            class="inline-block px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-bold uppercase tracking-wider rounded-full mb-3 border border-yellow-500/30">
                            New Feature
                        </span>
                        <h2 class="text-3xl md:text-4xl font-black text-white italic mb-2 tracking-tight">
                            THINK YOU KNOW <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-red-500">FORMULA
                                1?</span>
                        </h2>
                        <p class="text-gray-400 max-w-md text-lg">
                            Test your knowledge with our Daily Trivia or push your limits in Survival Mode.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                        <a href="{{ route('quiz') }}"
                            class="group relative px-8 py-4 bg-white text-black font-black uppercase text-sm tracking-widest rounded-xl hover:bg-gray-200 transition-all transform hover:-translate-y-1 text-center shadow-lg shadow-white/10">
                            Daily Trivia
                        </a>
                        <a href="{{ route('survival') }}"
                            class="group relative px-8 py-4 bg-red-600 text-white font-black uppercase text-sm tracking-widest rounded-xl hover:bg-red-500 transition-all transform hover:-translate-y-1 text-center shadow-lg shadow-red-600/20">
                            Survival Mode
                            <span class="absolute -top-2 -right-2 flex h-4 w-4">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-yellow-500"></span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured News Section -->
    <section class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-white">Latest News</h2>
                <a href="{{ route('news.index') }}" class="text-red-500 hover:text-red-400 text-sm font-medium">
                    View All →
                </a>
            </div>

            <livewire:news-feed :limit="6" :featured-only="false" />
        </div>
    </section>

    <!-- Two Column Layout: Calendar & Standings -->
    <section class="py-12 px-4 bg-gray-900/50">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Race Calendar -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-white">Race Calendar</h2>
                        <a href="{{ route('calendar') }}" class="text-red-500 hover:text-red-400 text-sm font-medium">
                            Full Calendar →
                        </a>
                    </div>
                    <livewire:race-calendar />
                </div>

                <!-- Driver Standings -->
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-white">Championship Standings</h2>
                        <a href="{{ route('standings') }}" class="text-red-500 hover:text-red-400 text-sm font-medium">
                            Full Standings →
                        </a>
                    </div>
                    <livewire:driver-standings :limit="10" />
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-2xl font-bold text-white mb-8 text-center">Explore by Category</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('news.index', ['category' => 'race_report']) }}"
                    class="group bg-gray-900 rounded-xl p-6 text-center hover:bg-gray-800 transition-all border border-gray-800 hover:border-red-800/50">
                    <div
                        class="w-12 h-12 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-600/30 transition-colors">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold mb-1">Race Reports</h3>
                    <p class="text-gray-500 text-sm">Post-race analysis</p>
                </a>

                <a href="{{ route('news.index', ['category' => 'technical']) }}"
                    class="group bg-gray-900 rounded-xl p-6 text-center hover:bg-gray-800 transition-all border border-gray-800 hover:border-red-800/50">
                    <div
                        class="w-12 h-12 bg-blue-600/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-600/30 transition-colors">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold mb-1">Technical</h3>
                    <p class="text-gray-500 text-sm">Car developments</p>
                </a>

                <a href="{{ route('news.index', ['category' => 'driver_profile']) }}"
                    class="group bg-gray-900 rounded-xl p-6 text-center hover:bg-gray-800 transition-all border border-gray-800 hover:border-red-800/50">
                    <div
                        class="w-12 h-12 bg-green-600/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-600/30 transition-colors">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold mb-1">Driver Profiles</h3>
                    <p class="text-gray-500 text-sm">In-depth features</p>
                </a>

                <a href="{{ route('news.index', ['category' => 'preview']) }}"
                    class="group bg-gray-900 rounded-xl p-6 text-center hover:bg-gray-800 transition-all border border-gray-800 hover:border-red-800/50">
                    <div
                        class="w-12 h-12 bg-yellow-600/20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-yellow-600/30 transition-colors">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-semibold mb-1">Previews</h3>
                    <p class="text-gray-500 text-sm">What to watch</p>
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
