<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
    xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    <!-- Home Page -->
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </url>

    <!-- News Index -->
    <url>
        <loc>{{ route('news.index') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </url>

    <!-- Calendar -->
    <url>
        <loc>{{ route('calendar') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </url>

    <!-- Standings -->
    <url>
        <loc>{{ route('standings') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </url>

    <!-- Drivers Index -->
    <url>
        <loc>{{ route('drivers.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </url>

    <!-- Teams Index -->
    <url>
        <loc>{{ route('teams.index') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
        <lastmod>{{ now()->toIso8601String() }}</lastmod>
    </url>

    <!-- News Articles -->
    @foreach ($news as $article)
        <url>
            <loc>{{ route('news.show', $article->slug) }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
            <lastmod>{{ $article->updated_at->toIso8601String() }}</lastmod>
        </url>
    @endforeach

    <!-- Races -->
    @foreach ($races as $race)
        <url>
            <loc>{{ route('races.show', $race->slug) }}</loc>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
            <lastmod>{{ $race->updated_at->toIso8601String() }}</lastmod>
        </url>
    @endforeach

    <!-- Drivers -->
    @foreach ($drivers as $driver)
        <url>
            <loc>{{ route('drivers.show', $driver->slug) }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
            <lastmod>{{ $driver->updated_at->toIso8601String() }}</lastmod>
        </url>
    @endforeach

    <!-- Teams -->
    @foreach ($teams as $team)
        <url>
            <loc>{{ route('teams.show', $team->slug) }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
            <lastmod>{{ $team->updated_at->toIso8601String() }}</lastmod>
        </url>
    @endforeach

</urlset>
