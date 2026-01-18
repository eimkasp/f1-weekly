@props(['drivers'])

@php
    $items = $drivers
        ->map(function ($driver, $index) {
            return [
                '@type' => 'Person',
                'name' => $driver->name,
                'url' => route('drivers.show', $driver),
                'nationality' => $driver->nationality ?? '',
            ];
        })
        ->toArray();

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => 'Formula 1 Drivers ' . date('Y'),
        'numberOfItems' => $drivers->count(),
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
