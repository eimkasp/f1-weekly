<x-layouts.app title="Export Calendar - F1 Weekly"
    description="Download the F1 {{ $season }} race calendar to Google Calendar, Apple Calendar, or Microsoft Outlook">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="text-sm text-gray-500 mb-4">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('calendar') }}" class="hover:text-white">Calendar</a>
                <span class="mx-2">/</span>
                <span class="text-white">Export</span>
            </nav>
            <h1 class="text-3xl font-bold text-white mb-2">Add F1 to Your Calendar</h1>
            <p class="text-gray-400">Never miss a race! Download the full season calendar or add individual races to
                your preferred calendar app.</p>
        </div>

        <!-- Full Season Export Card -->
        <div class="bg-gradient-to-br from-red-900/30 to-gray-900 rounded-xl p-6 mb-8 border border-red-600/30">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-white mb-2">Full {{ $season }} Season Calendar</h2>
                    <p class="text-gray-400 mb-4">Get all 24 races in one download. Includes practice sessions,
                        qualifying, sprints, and race times.</p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('calendar.export.full') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download .ics File
                        </a>
                        <span class="text-gray-500 text-sm self-center">Works with Apple Calendar, Google Calendar,
                            Outlook</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar App Instructions -->
        <div class="bg-gray-900 rounded-xl p-6 mb-8 border border-gray-800">
            <h3 class="text-lg font-bold text-white mb-4">How to Add to Your Calendar</h3>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Google Calendar -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M19.5 3h-3V1.5h-1.5V3h-6V1.5H7.5V3h-3C3.675 3 3 3.675 3 4.5v15c0 .825.675 1.5 1.5 1.5h15c.825 0 1.5-.675 1.5-1.5v-15c0-.825-.675-1.5-1.5-1.5zm0 16.5h-15v-10.5h15v10.5z" />
                        </svg>
                        <span class="font-medium text-white">Google Calendar</span>
                    </div>
                    <ol class="text-sm text-gray-400 space-y-1 list-decimal list-inside">
                        <li>Download the .ics file</li>
                        <li>Open Google Calendar</li>
                        <li>Click the + next to "Other calendars"</li>
                        <li>Select "Import"</li>
                        <li>Choose the downloaded file</li>
                    </ol>
                </div>

                <!-- Apple Calendar -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-300" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z" />
                        </svg>
                        <span class="font-medium text-white">Apple Calendar</span>
                    </div>
                    <ol class="text-sm text-gray-400 space-y-1 list-decimal list-inside">
                        <li>Download the .ics file</li>
                        <li>Double-click the file</li>
                        <li>Select which calendar to add to</li>
                        <li>Click "Add"</li>
                    </ol>
                </div>

                <!-- Outlook -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M24 7.387v10.478c0 .23-.08.424-.238.576-.16.154-.352.23-.578.23h-8.547v-6.959l1.6 1.229c.101.063.222.094.361.094.139 0 .26-.031.363-.094l6.8-5.063c.102-.063.17-.123.205-.18.035-.058.034-.137-.002-.237zm-.691-1.193c.057.095.057.19 0 .285l-7.104 5.285-7.104-5.285c-.057-.095-.057-.19 0-.285l.396-.395h13.416l.396.395zM9.803 21.398c.063-.063.094-.139.094-.227v-9.125l-3.504 2.604v6.748h3.183c.088 0 .164-.031.227-.093zm-6.803-5.52l3.504-2.604-3.504-2.605v5.209zm6.803-5.083c.063-.063.094-.138.094-.227v-.227l-3.504-2.605v5.664l3.183-2.378c.088-.063.164-.131.227-.227zm-6.803-5.083v5.209l3.504-2.604-3.504-2.605zm0 10.166v5.209l3.504-2.604-3.504-2.605z" />
                        </svg>
                        <span class="font-medium text-white">Microsoft Outlook</span>
                    </div>
                    <ol class="text-sm text-gray-400 space-y-1 list-decimal list-inside">
                        <li>Download the .ics file</li>
                        <li>Open Outlook Calendar</li>
                        <li>File → Open & Export → Import</li>
                        <li>Select iCalendar file</li>
                        <li>Choose the downloaded file</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Individual Race Cards -->
        <h3 class="text-lg font-bold text-white mb-4">Add Individual Races</h3>

        <div class="space-y-3">
            @forelse($races as $race)
                <div class="bg-gray-900 rounded-lg p-4 border border-gray-800 hover:border-gray-700 transition-colors">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="text-center w-12">
                                <div class="text-xl font-bold text-white">{{ $race->round }}</div>
                                <div class="text-xs text-gray-500 uppercase">Round</div>
                            </div>
                            <div class="w-px h-10 bg-gray-700"></div>
                            <div>
                                <h4 class="text-white font-medium">{{ $race->name }}</h4>
                                <div class="text-gray-500 text-sm">
                                    {{ $race->race_date->format('M j, Y') }}
                                    @if ($race->circuit)
                                        · {{ $race->circuit->name }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <!-- Google Calendar -->
                            <a href="{{ route('calendar.export.google', $race) }}" target="_blank"
                                class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors group"
                                title="Add to Google Calendar">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M19.5 3h-3V1.5h-1.5V3h-6V1.5H7.5V3h-3C3.675 3 3 3.675 3 4.5v15c0 .825.675 1.5 1.5 1.5h15c.825 0 1.5-.675 1.5-1.5v-15c0-.825-.675-1.5-1.5-1.5zm0 16.5h-15v-10.5h15v10.5z" />
                                </svg>
                            </a>

                            <!-- Outlook -->
                            <a href="{{ route('calendar.export.outlook', $race) }}" target="_blank"
                                class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors group"
                                title="Add to Outlook">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500" viewBox="0 0 24 24"
                                    fill="currentColor">
                                    <path
                                        d="M7.88 12.04q0 .45-.11.87-.1.41-.33.74-.22.33-.58.52-.37.2-.87.2t-.85-.2q-.35-.21-.57-.55-.22-.33-.33-.75-.1-.42-.1-.86t.1-.87q.1-.43.34-.76.22-.34.59-.54.36-.2.87-.2t.86.2q.35.21.57.55.22.34.31.77.1.43.1.88zM24 12v9.38q0 .46-.33.8-.33.32-.8.32H7.13q-.46 0-.8-.33-.32-.33-.32-.8V18H1q-.41 0-.7-.3-.3-.29-.3-.7V7q0-.41.3-.7Q.58 6 1 6h6.5V2.55q0-.44.3-.75.3-.3.75-.3h12.9q.44 0 .75.3.3.3.3.75V12zm-6-8.25v3h3v-3zm0 4.5v3h3v-3zm0 4.5v1.83l3.05-1.83zm-5.25-9v3h3.75v-3zm0 4.5v3h3.75v-3zm0 4.5v2.03l2.41 1.5 1.34-.53v-3zM9 3.75v3h2.25v-3zm0 4.5v3h2.25v-3zm0 4.5v3h2.25v-3zM7.88 12.04q0 .45-.11.87-.1.41-.33.74-.22.33-.58.52-.37.2-.87.2t-.85-.2q-.35-.21-.57-.55-.22-.33-.33-.75-.1-.42-.1-.86t.1-.87q.1-.43.34-.76.22-.34.59-.54.36-.2.87-.2t.86.2q.35.21.57.55.22.34.31.77.1.43.1.88z" />
                                </svg>
                            </a>

                            <!-- Download ICS -->
                            <a href="{{ route('calendar.export.race', $race) }}"
                                class="p-2 bg-gray-800 hover:bg-gray-700 rounded-lg transition-colors group"
                                title="Download .ics file">
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    No upcoming races found.
                </div>
            @endforelse
        </div>

        <!-- Back Link -->
        <div class="mt-8 pt-6 border-t border-gray-800">
            <a href="{{ route('calendar') }}" class="text-red-400 hover:text-red-300 inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Calendar
            </a>
        </div>
    </div>
</x-layouts.app>
