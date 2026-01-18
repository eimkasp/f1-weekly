<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'F1 Weekly') }} - Formula 1 News & Updates</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Alpine.js for countdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        f1: {
                            red: '#E10600',
                            darkRed: '#B30500',
                            dark: '#15151E',
                            gray: '#38383F'
                        }
                    }
                }
            }
        }
    </script>

    @livewireStyles
</head>

<body class="bg-gray-950 text-white min-h-screen font-sans">
    <!-- Navigation -->
    <header class="border-b border-gray-800">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3">
                    <div class="bg-red-600 rounded p-2">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold">
                        <span class="text-red-600">F1</span>
                        <span class="text-white">Weekly</span>
                    </span>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="/admin" class="text-gray-400 hover:text-white transition">News</a>
                    <a href="/admin/races" class="text-gray-400 hover:text-white transition">Races</a>
                    <a href="/admin/drivers" class="text-gray-400 hover:text-white transition">Drivers</a>
                    <a href="/admin/teams" class="text-gray-400 hover:text-white transition">Teams</a>
                </nav>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin') }}"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition font-medium">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition font-medium">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section with Race Countdown -->
    <main>
        <!-- Hero -->
        <section class="relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-950 to-black"></div>
            <div class="absolute inset-0 opacity-10"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')">
            </div>

            <div class="container mx-auto px-4 py-16 relative">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div>
                        <span
                            class="inline-block px-3 py-1 bg-red-600/20 text-red-500 rounded-full text-sm font-semibold mb-4">
                            🏎️ 2024 Season
                        </span>
                        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                            Your Ultimate
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">
                                Formula 1
                            </span>
                            News Hub
                        </h1>
                        <p class="text-gray-400 text-lg mb-8 max-w-xl">
                            Stay updated with the latest F1 news, race results, driver standings, and team updates.
                            AI-powered insights delivered to you daily.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="/admin/articles"
                                class="px-6 py-3 bg-red-600 hover:bg-red-700 rounded-lg transition font-semibold">
                                Read Latest News
                            </a>
                            <a href="/admin/races"
                                class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg transition font-semibold border border-gray-700">
                                View Calendar
                            </a>
                        </div>
                    </div>

                    <!-- Right Content - Race Countdown -->
                    <div>
                        <livewire:race-countdown size="large" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Stats Section -->
        <section class="border-y border-gray-800 bg-gray-900/50">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-500">24</div>
                        <div class="text-gray-400 text-sm">Races</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-500">20</div>
                        <div class="text-gray-400 text-sm">Drivers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-500">10</div>
                        <div class="text-gray-400 text-sm">Teams</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-500">5</div>
                        <div class="text-gray-400 text-sm">Continents</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">AI-Powered F1 Coverage</h2>
                    <p class="text-gray-400 max-w-2xl mx-auto">
                        Get comprehensive Formula 1 coverage with AI-generated articles, real-time data, and expert
                        analysis.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-red-800/50 transition">
                        <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">AI-Generated Articles</h3>
                        <p class="text-gray-400">
                            Automated news articles generated from race data, providing instant coverage of every
                            session.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-red-800/50 transition">
                        <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Live Standings</h3>
                        <p class="text-gray-400">
                            Real-time championship standings synced from official sources via the Jolpica F1 API.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-red-800/50 transition">
                        <div class="w-12 h-12 bg-red-600/20 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Race Countdown</h3>
                        <p class="text-gray-400">
                            Never miss a race with our live countdown timer showing time until the next Grand Prix.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- API Section -->
        <section class="py-16 bg-gray-900/50 border-y border-gray-800">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl font-bold mb-4">Developer API Available</h2>
                    <p class="text-gray-400 mb-8">
                        Access our comprehensive F1 data API for your own projects. 32 endpoints for races, drivers,
                        teams, standings, and more.
                    </p>
                    <div
                        class="bg-gray-950 rounded-xl p-6 border border-gray-800 text-left font-mono text-sm overflow-x-auto">
                        <div class="text-gray-500"># Fetch next race data</div>
                        <div class="text-green-400">curl http://localhost:8000/api/races/next</div>
                        <div class="mt-4 text-gray-500"># Sync driver standings</div>
                        <div class="text-green-400">curl -X POST http://localhost:8000/api/sync/standings/drivers</div>
                        <div class="mt-4 text-gray-500"># Generate AI article</div>
                        <div class="text-green-400">curl -X POST http://localhost:8000/api/articles/generate \</div>
                        <div class="text-green-400 pl-4">-H "Content-Type: application/json" \</div>
                        <div class="text-green-400 pl-4">-d '{"topic": "Monaco GP Preview"}'</div>
                    </div>
                    <div class="mt-6">
                        <a href="/api" class="text-red-500 hover:text-red-400 font-medium">
                            View Full API Documentation →
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-800 py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-2">
                    <span class="text-red-600 font-bold">F1</span>
                    <span class="text-white font-bold">Weekly</span>
                    <span class="text-gray-500 text-sm">© {{ date('Y') }}</span>
                </div>
                <div class="flex items-center space-x-6 text-sm text-gray-400">
                    <span>Powered by Laravel & Livewire</span>
                    <span class="text-gray-600">•</span>
                    <span>Data from Jolpica F1 API</span>
                    <span class="text-gray-600">•</span>
                    <span>Flags from Flagpedia</span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts

    <!-- Countdown Timer Script -->
    <script>
        function raceCountdown(targetDate) {
            return {
                days: '--',
                hours: '--',
                minutes: '--',
                seconds: '--',
                targetDate: new Date(targetDate),
                interval: null,

                startCountdown() {
                    this.updateCountdown();
                    this.interval = setInterval(() => this.updateCountdown(), 1000);
                },

                updateCountdown() {
                    const now = new Date();
                    const diff = this.targetDate - now;

                    if (diff <= 0) {
                        this.days = '00';
                        this.hours = '00';
                        this.minutes = '00';
                        this.seconds = '00';
                        if (this.interval) {
                            clearInterval(this.interval);
                        }
                        return;
                    }

                    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    this.days = String(days).padStart(2, '0');
                    this.hours = String(hours).padStart(2, '0');
                    this.minutes = String(minutes).padStart(2, '0');
                    this.seconds = String(seconds).padStart(2, '0');
                },

                destroy() {
                    if (this.interval) {
                        clearInterval(this.interval);
                    }
                }
            }
        }
    </script>
</body>

</html>
