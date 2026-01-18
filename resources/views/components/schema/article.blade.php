@props(['article'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'description' => $article->excerpt ?? Str::limit(strip_tags($article->content), 160),
        'image' => $article->featured_image
            ? asset('storage/' . $article->featured_image)
            : asset('images/og-default.jpg'),
        'datePublished' => $article->published_at?->toIso8601String(),
        'dateModified' => $article->updated_at?->toIso8601String(),
        'author' => [
            '@type' => 'Organization',
            'name' => 'F1 Weekly',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'F1 Weekly',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('favicon.png'),
            ],
        ],
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => route('news.show', $article->slug),
        ],
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
