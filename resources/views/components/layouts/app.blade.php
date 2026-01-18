<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'F1 Weekly' }} - Your AI-Powered F1 News Source</title>
    <meta name="description"
        content="{{ $description ?? 'Stay updated with the latest Formula 1 news, race reports, driver updates, and technical analysis - powered by AI.' }}">
    <meta name="keywords"
        content="{{ $keywords ?? 'Formula 1, F1, racing, motorsport, Grand Prix, drivers, teams, standings, live timing, race results, F1 news' }}">
    <meta name="author" content="F1 Weekly">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="F1 Weekly">
    <meta property="og:title" content="{{ $title ?? 'F1 Weekly' }}">
    <meta property="og:description" content="{{ $description ?? 'Your AI-Powered F1 News Source' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-default.jpg') }}">
    <meta property="og:locale" content="en_US">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'F1 Weekly' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Your AI-Powered F1 News Source' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-default.jpg') }}">

    <!-- Schema.org JSON-LD -->
    <x-schema.website :description="$description ?? 'AI-Powered Formula 1 news, race reports, and championship coverage'" />
    @stack('schema')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Additional head content -->
    @stack('head')
</head>

<body class="bg-black text-white antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-black/90 backdrop-blur-sm border-b border-gray-800" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">F1</span>
                        </div>
                        <span class="text-xl font-bold text-white">Weekly</span>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}"
                            class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('home') ? 'text-white' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('news.index') }}"
                            class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('news.*') ? 'text-white' : '' }}">
                            News
                        </a>
                        <a href="{{ route('calendar') }}"
                            class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('calendar') ? 'text-white' : '' }}">
                            Calendar
                        </a>
                        <a href="{{ route('standings') }}"
                            class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('standings') ? 'text-white' : '' }}">
                            Standings
                        </a>
                        <a href="{{ route('drivers.index') }}"
                            class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('drivers.*') ? 'text-white' : '' }}">
                            Drivers
                        </a>
                        <a href="{{ route('teams.index') }}"
                            class="text-gray-300 hover:text-white transition-colors {{ request()->routeIs('teams.*') ? 'text-white' : '' }}">
                            Teams
                        </a>
                        <a href="{{ route('odds') }}"
                            class="text-gray-300 hover:text-white transition-colors flex items-center gap-1 {{ request()->routeIs('odds') ? 'text-white' : '' }}">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Odds
                        </a>
                    </div>

                    <!-- Live Indicator (if race is live) -->
                    @if ($liveRace ?? false)
                        <a href="{{ route('race.live') }}"
                            class="hidden md:flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-full animate-pulse">
                            <span class="w-2 h-2 bg-white rounded-full mr-2"></span>
                            LIVE
                        </a>
                    @endif

                    <!-- Mobile menu button -->
                    <button type="button"
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800"
                        @click="mobileMenuOpen = !mobileMenuOpen">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="md:hidden" x-show="mobileMenuOpen" x-cloak x-transition>
                <div class="px-2 pt-2 pb-3 space-y-1 bg-gray-900 border-t border-gray-800">
                    <a href="{{ route('home') }}"
                        class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md">Home</a>
                    <a href="{{ route('news.index') }}"
                        class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md">News</a>
                    <a href="{{ route('calendar') }}"
                        class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md">Calendar</a>
                    <a href="{{ route('standings') }}"
                        class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md">Standings</a>
                    <a href="{{ route('drivers.index') }}"
                        class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md">Drivers</a>
                    <a href="{{ route('teams.index') }}"
                        class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md">Teams</a>
                    <a href="{{ route('odds') }}"
                        class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Odds
                    </a>
                    @if ($liveRace ?? false)
                        <a href="{{ route('race.live') }}"
                            class="block px-3 py-2 bg-red-600 text-white font-medium rounded-md">🔴 LIVE Race</a>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 border-t border-gray-800 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- About -->
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xl">F1</span>
                            </div>
                            <span class="text-xl font-bold text-white">Weekly</span>
                        </div>
                        <p class="text-gray-400 text-sm max-w-md mb-4">
                            Your AI-powered source for Formula 1 news, analysis, and updates.
                            Stay ahead of the grid with intelligent, real-time coverage of the world's premier
                            motorsport championship.
                        </p>
                        <p class="text-gray-500 text-xs">
                            Covering all {{ date('Y') }} Formula 1 races including Australian GP, Monaco GP,
                            British GP,
                            and more. Get driver standings, team rankings, race results, and prediction market odds.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('news.index') }}"
                                    class="text-gray-400 hover:text-white transition-colors">Latest News</a></li>
                            <li><a href="{{ route('calendar') }}"
                                    class="text-gray-400 hover:text-white transition-colors">Race Calendar</a></li>
                            <li><a href="{{ route('standings') }}"
                                    class="text-gray-400 hover:text-white transition-colors">Driver Standings</a></li>
                            <li><a href="{{ route('standings.constructors') }}"
                                    class="text-gray-400 hover:text-white transition-colors">Constructor Standings</a>
                            </li>
                            <li><a href="{{ route('drivers.index') }}"
                                    class="text-gray-400 hover:text-white transition-colors">All Drivers</a></li>
                            <li><a href="{{ route('teams.index') }}"
                                    class="text-gray-400 hover:text-white transition-colors">All Teams</a></li>
                            <li><a href="{{ route('odds') }}"
                                    class="text-gray-400 hover:text-white transition-colors">Prediction Markets</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="text-white font-semibold mb-4">News Categories</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('news.index', ['category' => 'race_report']) }}"
                                    class="text-gray-400 hover:text-white transition-colors">Race Reports</a></li>
                            <li><a href="{{ route('news.index', ['category' => 'technical']) }}"
                                    class="text-gray-400 hover:text-white transition-colors">Technical Analysis</a>
                            </li>
                            <li><a href="{{ route('news.index', ['category' => 'breaking']) }}"
                                    class="text-gray-400 hover:text-white transition-colors">Breaking News</a></li>
                            <li><a href="{{ route('news.index', ['category' => 'preview']) }}"
                                    class="text-gray-400 hover:text-white transition-colors">Race Previews</a></li>
                            <li><a href="{{ route('news.index', ['category' => 'driver']) }}"
                                    class="text-gray-400 hover:text-white transition-colors">Driver News</a></li>
                            <li><a href="{{ route('news.index', ['category' => 'team']) }}"
                                    class="text-gray-400 hover:text-white transition-colors">Team Updates</a></li>
                        </ul>
                    </div>
                </div>

                <!-- SEO Rich Footer Content -->
                <div class="mt-10 pt-8 border-t border-gray-800">
                    <div class="grid md:grid-cols-2 gap-6 text-xs text-gray-500">
                        <div>
                            <h4 class="text-gray-400 font-medium mb-2">About Formula 1</h4>
                            <p>
                                Formula 1, also known as F1, is the highest class of international racing for open-wheel
                                single-seater formula racing cars. The FIA Formula One World Championship has been one
                                of the
                                premier forms of motorsport since its inaugural season in 1950. Follow the latest from
                                teams
                                like Ferrari, Red Bull Racing, Mercedes, McLaren, Aston Martin, Alpine, Williams, Haas,
                                Kick Sauber, and RB.
                            </p>
                        </div>
                        <div>
                            <h4 class="text-gray-400 font-medium mb-2">{{ date('Y') }} Season Highlights</h4>
                            <p>
                                Track the championship battle between Max Verstappen, Lewis Hamilton, Charles Leclerc,
                                Lando Norris, and other top drivers. Get real-time updates on qualifying sessions,
                                sprint races, and Grand Prix events from circuits around the world including Monaco,
                                Silverstone, Spa-Francorchamps, Monza, and more.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Resources & Feeds -->
                <div class="mt-8 pt-6 border-t border-gray-800">
                    <div class="flex flex-wrap gap-4 text-xs">
                        <a href="{{ route('sitemap') }}" class="text-gray-500 hover:text-gray-400">Sitemap</a>
                        <a href="{{ route('feed.rss') }}" class="text-gray-500 hover:text-gray-400">RSS Feed</a>
                        <a href="{{ route('calendar.export.options') }}"
                            class="text-gray-500 hover:text-gray-400">Calendar Export</a>
                        <a href="/llms.txt" class="text-gray-500 hover:text-gray-400">LLMs.txt</a>
                    </div>
                </div>

                <!-- Copyright & Disclaimer -->
                <div
                    class="mt-8 pt-6 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-gray-500 text-sm">
                            © {{ date('Y') }} F1 Weekly. All rights reserved.
                        </p>
                        <p class="text-gray-600 text-xs mt-1">
                            F1 Weekly is an independent fan site. Formula 1, F1, and related marks are trademarks of
                            Formula One Licensing BV.
                        </p>
                    </div>
                    <p class="text-gray-600 text-xs">
                        Powered by AI • Data from Ergast API & OpenF1
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts

    <!-- Global Scripts -->
    <script>
        // Countdown component for Alpine.js
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (targetDate) => ({
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                interval: null,
                init() {
                    this.updateCountdown();
                    this.interval = setInterval(() => {
                        this.updateCountdown();
                    }, 1000);
                },
                updateCountdown() {
                    const now = new Date().getTime();
                    const target = new Date(targetDate).getTime();
                    const distance = target - now;

                    if (distance < 0) {
                        this.days = 0;
                        this.hours = 0;
                        this.minutes = 0;
                        this.seconds = 0;
                        if (this.interval) clearInterval(this.interval);
                        return;
                    }

                    this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                },
                destroy() {
                    if (this.interval) clearInterval(this.interval);
                }
            }));
        });
    </script>

    @stack('scripts')
</body>

</html>
