@props(['race'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'SportsEvent',
        'name' => $race->name,
        'description' => 'Formula 1 ' . $race->name . ' - ' . $race->season . ' Season',
        'startDate' => $race->race_date?->toIso8601String(),
        'endDate' => $race->race_date?->toIso8601String(),
        'eventStatus' => $race->isCompleted()
            ? 'https://schema.org/EventScheduled'
            : 'https://schema.org/EventScheduled',
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'location' => [
            '@type' => 'Place',
            'name' => $race->circuit?->name ?? ($race->circuit_name ?? 'TBA'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => $race->country ?? '',
            ],
        ],
        'organizer' => [
            '@type' => 'Organization',
            'name' => 'Formula One World Championship',
            'url' => 'https://www.formula1.com',
        ],
        'performer' => [
            '@type' => 'SportsTeam',
            'name' => 'Formula 1 Teams',
        ],
    ];

    if ($race->circuit?->lat && $race->circuit?->lng) {
        $schema['location']['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $race->circuit->lat,
            'longitude' => $race->circuit->lng,
        ];
    }
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
