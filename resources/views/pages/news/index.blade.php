<x-layouts.app title="News - F1 Weekly">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">
                @if ($category)
                    {{ str_replace('_', ' ', ucfirst($category)) }} News
                @else
                    Latest F1 News
                @endif
            </h1>
            <p class="text-gray-400">Stay updated with the latest Formula 1 news and analysis</p>
        </div>

        <livewire:news-feed :category="$category" :per-page="12" />
    </div>
</x-layouts.app>
