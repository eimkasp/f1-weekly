<div class="news-feed">
    <!-- Category Filter -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button wire:click="setCategory(null)"
            class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors
                       {{ !$category ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
            All
        </button>
        @foreach ($categories as $key => $label)
            <button wire:click="setCategory('{{ $key }}')"
                class="px-3 py-1.5 rounded-full text-sm font-medium transition-colors
                           {{ $category === $key ? 'bg-red-600 text-white' : 'bg-gray-800 text-gray-400 hover:bg-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- News Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($news as $article)
            <article
                class="bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-red-800/50 transition-all group">
                @if ($article->featured_image)
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $article->featured_image }}" alt="{{ $article->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @if ($article->is_featured)
                            <div class="absolute top-3 left-3">
                                <span
                                    class="px-2 py-1 bg-red-600 text-white text-xs font-semibold rounded">Featured</span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="p-5">
                    <!-- Tags -->
                    @if ($article->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach ($article->tags->take(3) as $tag)
                                <span class="px-2 py-0.5 bg-gray-800 text-gray-400 text-xs rounded">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Category Badge -->
                    <div class="mb-2">
                        <span class="text-red-500 text-xs font-semibold uppercase tracking-wider">
                            {{ str_replace('_', ' ', $article->category) }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h3
                        class="text-lg font-bold text-white mb-2 line-clamp-2 group-hover:text-red-500 transition-colors">
                        <a href="{{ route('news.show', $article) }}">
                            {{ $article->title }}
                        </a>
                    </h3>

                    <!-- Excerpt -->
                    @if ($article->excerpt)
                        <p class="text-gray-400 text-sm mb-4 line-clamp-3">
                            {{ $article->excerpt }}
                        </p>
                    @endif

                    <!-- Meta -->
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div class="flex items-center space-x-4">
                            <span>{{ $article->published_at->diffForHumans() }}</span>
                            @if ($article->reading_time)
                                <span>{{ $article->reading_time }} min read</span>
                            @endif
                        </div>

                        @if ($article->view_count > 0)
                            <span>{{ number_format($article->view_count) }} views</span>
                        @endif
                    </div>

                    <!-- Related Entities -->
                    @if ($article->drivers->isNotEmpty() || $article->teams->isNotEmpty())
                        <div class="mt-4 pt-4 border-t border-gray-800">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($article->drivers->take(2) as $driver)
                                    <a href="{{ route('driver.show', $driver) }}"
                                        class="text-xs text-gray-400 hover:text-white transition-colors">
                                        {{ $driver->short_name }}
                                    </a>
                                @endforeach
                                @foreach ($article->teams->take(2) as $team)
                                    <a href="{{ route('team.show', $team) }}"
                                        class="text-xs text-gray-400 hover:text-white transition-colors">
                                        {{ $team->short_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <p class="text-gray-500">No news articles found.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($news->hasPages())
        <div class="mt-8">
            {{ $news->links() }}
        </div>
    @endif
</div>
