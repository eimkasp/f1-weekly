@props(['title' => 'Formula 1 World Championship'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'SportsEvent',
        'name' => $title . ' ' . date('Y'),
        'sport' => 'Formula 1 Racing',
        'url' => route('standings'),
    ];
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
