@props(['team'])

@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'SportsTeam',
        'name' => $team->name,
        'sport' => 'Formula 1',
        'description' => 'Formula 1 constructor team',
        'url' => route('teams.show', $team->slug ?? $team->id),
    ];

    if ($team->nationality) {
        $schema['location'] = [
            '@type' => 'Place',
            'name' => $team->nationality,
        ];
    }

    if ($team->logo) {
        $schema['logo'] = asset('storage/' . $team->logo);
    }
@endphp

<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
