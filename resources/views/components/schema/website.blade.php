@props([
    'title' => 'F1 Weekly',
    'description' => 'AI-Powered Formula 1 news, race reports, and championship coverage',
])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'F1 Weekly',
        'url' => url('/'),
        'description' => $description,
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'F1 Weekly',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('favicon.png'),
            ],
        ],
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/news') . '?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
