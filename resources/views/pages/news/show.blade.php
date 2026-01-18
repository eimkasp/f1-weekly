<x-layouts.app :title="$article->meta_title ?? $article->title" :description="$article->meta_description ?? $article->excerpt" :og-image="$article->featured_image">

    <article class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('news.index') }}" class="hover:text-white">News</a></li>
                <li>/</li>
                <li class="text-gray-400">{{ Str::limit($article->title, 30) }}</li>
            </ol>
        </nav>

        <!-- Article Header -->
        <header class="mb-8">
            <!-- Category -->
            <div class="mb-4">
                <a href="{{ route('news.index', ['category' => $article->category]) }}"
                    class="inline-flex items-center px-3 py-1 rounded-full bg-red-600/20 text-red-500 text-sm font-medium">
                    {{ str_replace('_', ' ', ucfirst($article->category)) }}
                </a>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                {{ $article->title }}
            </h1>

            <!-- Excerpt -->
            @if ($article->excerpt)
                <p class="text-xl text-gray-400 mb-6">
                    {{ $article->excerpt }}
                </p>
            @endif

            <!-- Meta -->
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                <time datetime="{{ $article->published_at->toISOString() }}">
                    {{ $article->published_at->format('F j, Y') }}
                </time>

                @if ($article->reading_time)
                    <span>•</span>
                    <span>{{ $article->reading_time }} min read</span>
                @endif

                <span>•</span>
                <span>{{ number_format($article->view_count) }} views</span>
            </div>
        </header>

        <!-- Featured Image -->
        @if ($article->featured_image)
            <figure class="mb-8">
                <img src="{{ $article->featured_image }}" alt="{{ $article->title }}"
                    class="w-full rounded-xl object-cover max-h-96">
                @if ($article->image_caption)
                    <figcaption class="mt-2 text-sm text-gray-500 text-center">
                        {{ $article->image_caption }}
                    </figcaption>
                @endif
            </figure>
        @endif

        <!-- Tags -->
        @if ($article->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                @foreach ($article->tags as $tag)
                    <span class="px-3 py-1 bg-gray-800 text-gray-400 text-sm rounded-full">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <!-- Article Content -->
        <div class="prose prose-invert prose-lg max-w-none mb-12">
            {!! $article->content !!}
        </div>

        <!-- Related Entities -->
        @if ($article->drivers->isNotEmpty() || $article->teams->isNotEmpty() || $article->races->isNotEmpty())
            <div class="border-t border-gray-800 pt-8 mb-8">
                <h3 class="text-lg font-semibold text-white mb-4">Related</h3>
                <div class="flex flex-wrap gap-3">
                    @foreach ($article->drivers as $driver)
                        <a href="{{ route('driver.show', $driver) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                            @if ($driver->image_url)
                                <img src="{{ $driver->image_url }}" alt="{{ $driver->full_name }}"
                                    class="w-8 h-8 rounded-full mr-2 object-cover">
                            @endif
                            <span class="text-white">{{ $driver->full_name }}</span>
                        </a>
                    @endforeach

                    @foreach ($article->teams as $team)
                        <a href="{{ route('team.show', $team) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                            <div class="w-3 h-3 rounded mr-2" style="background-color: {{ $team->color ?? '#666' }}">
                            </div>
                            <span class="text-white">{{ $team->name }}</span>
                        </a>
                    @endforeach

                    @foreach ($article->races as $race)
                        <a href="{{ route('race.show', $race) }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <span class="text-white">{{ $race->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Share -->
        <div class="border-t border-gray-800 pt-8 mb-12">
            <h3 class="text-lg font-semibold text-white mb-4">Share this article</h3>
            <div class="flex gap-3">
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}"
                    target="_blank"
                    class="px-4 py-2 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white">
                    Twitter
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                    target="_blank"
                    class="px-4 py-2 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white">
                    Facebook
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($article->title) }}"
                    target="_blank"
                    class="px-4 py-2 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors text-gray-400 hover:text-white">
                    LinkedIn
                </a>
            </div>
        </div>
    </article>

    <!-- Related Articles -->
    @if ($related->isNotEmpty())
        <section class="bg-gray-900/50 py-12">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-2xl font-bold text-white mb-8">Related Articles</h2>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                    @foreach ($related as $relatedArticle)
                        <article
                            class="bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-red-800/50 transition-all group">
                            @if ($relatedArticle->featured_image)
                                <div class="h-32 overflow-hidden">
                                    <img src="{{ $relatedArticle->featured_image }}"
                                        alt="{{ $relatedArticle->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @endif
                            <div class="p-4">
                                <span class="text-red-500 text-xs font-semibold uppercase">
                                    {{ str_replace('_', ' ', $relatedArticle->category) }}
                                </span>
                                <h3
                                    class="text-white font-medium mt-1 line-clamp-2 group-hover:text-red-500 transition-colors">
                                    <a href="{{ route('news.show', $relatedArticle) }}">
                                        {{ $relatedArticle->title }}
                                    </a>
                                </h3>
                                <div class="text-gray-500 text-xs mt-2">
                                    {{ $relatedArticle->published_at->diffForHumans() }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @push('head')
        <!-- Article Structured Data -->
        <script type="application/ld+json">
        {!! json_encode($article->getStructuredData()) !!}
        </script>
    @endpush
</x-layouts.app>
