@props(['teams'])

@php
    $items = $teams
        ->map(function ($team, $index) {
            return [
                '@type' => 'SportsTeam',
                'name' => $team->name,
                'url' => route('teams.show', $team),
                'sport' => 'Formula 1 Racing',
            ];
        })
        ->toArray();

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Formula 1 Teams ' . date('Y'),
        'numberOfItems' => $teams->count(),
        'itemListElement' => [],
    ];

    foreach ($items as $index => $item) {
        $schema['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => $item,
        ];
    }
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
