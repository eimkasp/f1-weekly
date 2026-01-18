@props(['driver'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $driver->name,
        'givenName' => $driver->first_name ?? (explode(' ', $driver->name)[0] ?? ''),
        'familyName' => $driver->last_name ?? (explode(' ', $driver->name)[1] ?? ''),
        'nationality' => $driver->nationality ?? '',
        'jobTitle' => 'Formula 1 Driver',
        'affiliation' => $driver->team
            ? [
                '@type' => 'SportsTeam',
                'name' => $driver->team->name,
            ]
            : null,
        'sameAs' => array_filter([$driver->wikipedia_url ?? null]),
    ];

    if ($driver->driver_number) {
        $schema['identifier'] = $driver->driver_number;
    }

    // Remove null values
    $schema = array_filter($schema, fn($value) => $value !== null);
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
