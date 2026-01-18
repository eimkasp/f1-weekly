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
    <div class="min-h-screen flex flex-col pb-16 md:pb-0" x-data="{
        mobileMenuOpen: false,
        moreSheetOpen: false,
        standingsSheetOpen: false,
        closeAllSheets() {
            this.moreSheetOpen = false;
            this.standingsSheetOpen = false;
        }
    }"
        @keydown.escape.window="closeAllSheets()">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-black/95 backdrop-blur-md border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14 md:h-16">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-red-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-lg md:text-xl">F1</span>
                        </div>
                        <span class="text-lg md:text-xl font-bold text-white">Weekly</span>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('home') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                            Home
                        </a>
                        <a href="{{ route('news.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('news.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                            News
                        </a>
                        <a href="{{ route('calendar') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('calendar') || request()->routeIs('races.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                            Calendar
                        </a>
                        <a href="{{ route('games') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('games') || request()->routeIs('quiz') || request()->routeIs('survival') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                            Play
                        </a>

                        <!-- Standings Dropdown -->
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true"
                            @mouseleave="open = false">
                            <button
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-1 {{ request()->routeIs('standings*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                                Standings
                                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute top-full left-0 mt-1 w-48 bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden z-50">
                                <a href="{{ route('standings') }}"
                                    class="block px-4 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                                    <div class="font-medium">Drivers</div>
                                    <div class="text-xs text-gray-500">Championship standings</div>
                                </a>
                                <a href="{{ route('standings.constructors') }}"
                                    class="block px-4 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors border-t border-gray-800">
                                    <div class="font-medium">Constructors</div>
                                    <div class="text-xs text-gray-500">Team standings</div>
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('drivers.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('drivers.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                            Drivers
                        </a>
                        <a href="{{ route('teams.index') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teams.*') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800/50' }}">
                            Teams
                        </a>
                        <a href="{{ route('odds') }}"
                            class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5 {{ request()->routeIs('odds') ? 'bg-purple-900/50 text-purple-300' : 'text-gray-400 hover:text-purple-300 hover:bg-purple-900/30' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            Odds
                        </a>
                    </div>

                    <!-- Live Indicator (Desktop) -->
                    @if ($liveRace ?? false)
                        <a href="{{ route('race.live') }}"
                            class="hidden md:flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-full animate-pulse">
                            <span class="w-2 h-2 bg-white rounded-full mr-2"></span>
                            LIVE
                        </a>
                    @endif

                    <!-- Mobile: Live indicator only -->
                    @if ($liveRace ?? false)
                        <a href="{{ route('race.live') }}"
                            class="md:hidden flex items-center px-2.5 py-1 bg-red-600 text-white text-xs font-medium rounded-full animate-pulse">
                            <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5"></span>
                            LIVE
                        </a>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>

        <!-- Mobile Bottom Navigation -->
        <nav
            class="md:hidden fixed bottom-0 left-0 right-0 bg-black/95 backdrop-blur-md border-t border-gray-800 z-50 safe-area-bottom">
            <div class="grid grid-cols-5 h-16">
                <!-- Home -->
                <a href="{{ route('home') }}"
                    class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('home') ? 'text-white' : 'text-gray-500' }}">
                    <svg class="w-6 h-6" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('home') ? '0' : '1.5' }}"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-[10px] font-medium">Home</span>
                </a>

                <!-- Calendar -->
                <a href="{{ route('calendar') }}"
                    class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('calendar') || request()->routeIs('races.*') ? 'text-white' : 'text-gray-500' }}">
                    <svg class="w-6 h-6"
                        fill="{{ request()->routeIs('calendar') || request()->routeIs('races.*') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('calendar') || request()->routeIs('races.*') ? '0' : '1.5' }}"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-[10px] font-medium">Races</span>
                </a>

                <!-- Standings (with bottom sheet) -->
                <button @click="standingsSheetOpen = true; moreSheetOpen = false"
                    class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('standings*') ? 'text-white' : 'text-gray-500' }}">
                    <svg class="w-6 h-6" fill="{{ request()->routeIs('standings*') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('standings*') ? '0' : '1.5' }}"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="text-[10px] font-medium">Standings</span>
                </button>

                <!-- News -->
                <a href="{{ route('news.index') }}"
                    class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('news.*') ? 'text-white' : 'text-gray-500' }}">
                    <svg class="w-6 h-6" fill="{{ request()->routeIs('news.*') ? 'currentColor' : 'none' }}"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="{{ request()->routeIs('news.*') ? '0' : '1.5' }}"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <span class="text-[10px] font-medium">News</span>
                    </button>

                    <!-- More (with bottom sheet) -->
                    <button @click="moreSheetOpen = true; standingsSheetOpen = false"
                        class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('drivers.*') || request()->routeIs('teams.*') || request()->routeIs('odds') ? 'text-white' : 'text-gray-500' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="text-[10px] font-medium">More</span>
                    </button>
            </div>
        </nav>

        <!-- Standings Bottom Sheet -->
        <div x-show="standingsSheetOpen" x-cloak class="md:hidden fixed inset-0 z-[60]"
            @click.self="standingsSheetOpen = false">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-show="standingsSheetOpen"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @click="standingsSheetOpen = false"></div>

            <!-- Sheet -->
            <div class="absolute bottom-0 left-0 right-0 bg-gray-900 rounded-t-2xl safe-area-bottom"
                x-show="standingsSheetOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full">
                <!-- Handle -->
                <div class="flex justify-center pt-3 pb-2">
                    <div class="w-10 h-1 bg-gray-700 rounded-full"></div>
                </div>

                <!-- Header -->
                <div class="px-4 pb-3 border-b border-gray-800">
                    <h3 class="text-lg font-semibold text-white">Standings</h3>
                </div>

                <!-- Options -->
                <div class="py-2">
                    <a href="{{ route('standings') }}"
                        class="flex items-center gap-4 px-4 py-4 hover:bg-gray-800 transition-colors"
                        @click="standingsSheetOpen = false">
                        <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Driver Standings</div>
                            <div class="text-sm text-gray-500">Championship points & positions</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="{{ route('standings.constructors') }}"
                        class="flex items-center gap-4 px-4 py-4 hover:bg-gray-800 transition-colors"
                        @click="standingsSheetOpen = false">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Constructor Standings</div>
                            <div class="text-sm text-gray-500">Team championship points</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Cancel -->
                <div class="px-4 py-3 border-t border-gray-800">
                    <button @click="standingsSheetOpen = false"
                        class="w-full py-3 text-center text-gray-400 font-medium rounded-xl bg-gray-800 hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- More Bottom Sheet -->
        <div x-show="moreSheetOpen" x-cloak class="md:hidden fixed inset-0 z-[60]"
            @click.self="moreSheetOpen = false">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" x-show="moreSheetOpen"
                x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                @click="moreSheetOpen = false"></div>

            <!-- Sheet -->
            <div class="absolute bottom-0 left-0 right-0 bg-gray-900 rounded-t-2xl safe-area-bottom max-h-[80vh] overflow-y-auto"
                x-show="moreSheetOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full">
                <!-- Handle -->
                <div class="flex justify-center pt-3 pb-2 sticky top-0 bg-gray-900">
                    <div class="w-10 h-1 bg-gray-700 rounded-full"></div>
                </div>

                <!-- Header -->
                <div class="px-4 pb-3 border-b border-gray-800 sticky top-6 bg-gray-900">
                    <h3 class="text-lg font-semibold text-white">More</h3>
                </div>

                <!-- Primary Options -->
                <div class="py-2">
                    <a href="{{ route('drivers.index') }}"
                        class="flex items-center gap-4 px-4 py-4 hover:bg-gray-800 transition-colors"
                        @click="moreSheetOpen = false">
                        <div class="w-10 h-10 bg-red-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Drivers</div>
                            <div class="text-sm text-gray-500">All F1 drivers & profiles</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="{{ route('teams.index') }}"
                        class="flex items-center gap-4 px-4 py-4 hover:bg-gray-800 transition-colors"
                        @click="moreSheetOpen = false">
                        <div class="w-10 h-10 bg-green-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Teams</div>
                            <div class="text-sm text-gray-500">Constructor teams & info</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    <a href="{{ route('odds') }}"
                        class="flex items-center gap-4 px-4 py-4 hover:bg-gray-800 transition-colors"
                        @click="moreSheetOpen = false">
                        <div class="w-10 h-10 bg-purple-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Prediction Odds</div>
                            <div class="text-sm text-gray-500">Polymarket betting odds</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <a href="{{ route('games') }}"
                        class="flex items-center gap-4 px-4 py-4 hover:bg-gray-800 transition-colors"
                        @click="moreSheetOpen = false">
                        <div class="w-10 h-10 bg-yellow-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">F1 Arcade</div>
                            <div class="text-sm text-gray-500">Trivia & Survival Games</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Divider with label -->
                <div class="px-4 py-2">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">Resources</div>
                </div>

                <!-- Secondary Options -->
                <div class="pb-2">
                    <a href="{{ route('calendar.export.options') }}"
                        class="flex items-center gap-4 px-4 py-3 hover:bg-gray-800 transition-colors"
                        @click="moreSheetOpen = false">
                        <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Export Calendar</div>
                            <div class="text-sm text-gray-500">Add races to your calendar</div>
                        </div>
                    </a>
                    <a href="{{ route('feed.rss') }}"
                        class="flex items-center gap-4 px-4 py-3 hover:bg-gray-800 transition-colors"
                        @click="moreSheetOpen = false">
                        <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 5c7.18 0 13 5.82 13 13M6 11a7 7 0 017 7m-6 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">RSS Feed</div>
                            <div class="text-sm text-gray-500">Subscribe to news updates</div>
                        </div>
                    </a>
                    <a href="{{ route('sitemap') }}"
                        class="flex items-center gap-4 px-4 py-3 hover:bg-gray-800 transition-colors"
                        @click="moreSheetOpen = false">
                        <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">Sitemap</div>
                            <div class="text-sm text-gray-500">Browse all pages</div>
                        </div>
                    </a>
                </div>

                <!-- Cancel -->
                <div class="px-4 py-3 border-t border-gray-800 sticky bottom-0 bg-gray-900">
                    <button @click="moreSheetOpen = false"
                        class="w-full py-3 text-center text-gray-400 font-medium rounded-xl bg-gray-800 hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

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
