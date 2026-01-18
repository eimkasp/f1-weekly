<x-layouts.app title="Race Calendar - F1 Weekly">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ now()->year }} F1 Calendar</h1>
                <p class="text-gray-400">Complete race schedule for the Formula 1 season</p>
            </div>
            <a href="{{ route('calendar.export.options') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition-colors self-start">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Add to Calendar
            </a>
        </div>

        <livewire:race-calendar />
    </div>
</x-layouts.app>
