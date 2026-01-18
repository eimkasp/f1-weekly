@props(['items', 'name', 'itemType' => 'Thing'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $name,
        'numberOfItems' => count($items),
        'itemListElement' => [],
    ];

    foreach ($items as $index => $item) {
        $listItem = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => $item,
        ];
        $schema['itemListElement'][] = $listItem;
    }
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
