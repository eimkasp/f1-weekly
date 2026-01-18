# AI Coding Agent Guidelines - F1 Weekly Platform

> This document provides context and guidelines for AI coding agents working on the F1 Weekly Platform. Follow these instructions for consistent, high-quality contributions.

## 🎯 Project Vision

**F1 Weekly** is an autonomous, AI-powered Formula 1 news, data, and analysis platform. The system generates engaging F1 content 24/7 with minimal human intervention, leveraging real-time data from official F1 APIs and multiple motorsport sources.

### Core Principles
- **Autonomous Operation** - Content generation runs without human intervention
- **Data-Driven** - Every article is backed by real F1 data and statistics
- **Quality First** - All content must meet quality standards before publishing
- **Real-Time** - React to live races, qualifying sessions, and breaking news
- **Scalable** - Handle traffic spikes during race weekends

### Design Philosophy
- **Accuracy** - F1 facts must be verified against our database (lap times, positions, stats)
- **Engagement** - Headlines and content must capture motorsport fan attention
- **SEO Optimized** - Every article optimized for search discovery
- **Mobile First** - 70%+ traffic is mobile, especially during race weekends
- **Performance** - Sub-2s page loads even during race weekends
- **Visual Consistency** - Follow the [Design System](#-design-system) for all UI components

---

## 🏗️ Architecture Overview

### Tech Stack
| Layer | Technology |
|-------|------------|
| Framework | Laravel 12 |
| Admin Panel | Filament v4 |
| Frontend | Livewire 3, Alpine.js, Tailwind CSS v4 |
| Database | SQLite (dev) / MySQL (prod) |
| Queue | Database / Redis |
| AI | OpenAI GPT-4 / Claude |
| Data APIs | Ergast F1 API, OpenF1 API, API-Sports F1 |
| Cache | Redis / Laravel Cache |

### Key Directories
```
app/
├── Console/Commands/           # Artisan commands (auto-registered)
├── Filament/                   # Admin panel resources
│   ├── Resources/              # CRUD for Drivers, Teams, Races, News
│   └── Widgets/                # Dashboard widgets (standings, upcoming)
├── Http/Controllers/           # Web controllers
├── Jobs/                       # Background jobs
│   └── AutonomousContentPipeline/  # News automation jobs
├── Livewire/                   # Livewire components
│   ├── RaceCalendar.php        # Race schedule component
│   ├── DriverStandings.php     # Live standings
│   ├── NewsFeed.php            # Latest news
│   └── LiveRaceTracker.php     # Live race updates
├── Models/                     # Eloquent models
└── Services/                   # Business logic services
    ├── F1DataService.php       # API data fetching
    ├── AINewsGeneratorService.php
    └── StandingsService.php

resources/
├── css/
│   └── design-system.css       # 🎨 Design tokens & component styles
├── js/
└── views/
    ├── components/             # Blade components
    ├── livewire/               # Livewire views
    └── pages/                  # Full pages
```

---

## 🏎️ F1 Data Models

### Driver
```php
// Primary fields
$driver = Driver::create([
    'external_id' => 34,                    // API-Sports ID
    'name' => 'Charles Leclerc',
    'abbr' => 'LEC',
    'number' => 16,
    'nationality' => 'Monegasque',
    'date_of_birth' => '1997-10-16',
    'image' => 'https://...',
    'biography' => 'AI-generated bio',
]);

// Relationships
$driver->team();           // Current team (BelongsTo)
$driver->raceResults();    // HasMany race results
$driver->news();           // MorphToMany news articles
$driver->standings();      // HasMany standing entries
```

### Team
```php
$team = Team::create([
    'external_id' => 3,
    'name' => 'Scuderia Ferrari',
    'short_name' => 'Ferrari',
    'logo' => 'https://...',
    'base' => 'Maranello, Italy',
    'team_principal' => 'Frédéric Vasseur',
    'chassis' => 'SF-24',
    'power_unit' => 'Ferrari',
    'founded' => 1950,
    'world_championships' => 16,
]);

// Relationships
$team->drivers();          // HasMany drivers
$team->raceResults();      // HasMany through drivers
$team->news();             // MorphToMany news
$team->constructorStandings();
```

### Race
```php
$race = Race::create([
    'external_id' => 1488,
    'season' => 2025,
    'round' => 1,
    'name' => 'Bahrain Grand Prix',
    'circuit_id' => $circuit->id,
    'date' => '2025-03-02',
    'time' => '15:00:00',
    'status' => 'scheduled',  // scheduled|live|completed|cancelled
    'laps' => 57,
    'distance' => '308.238 km',
]);

// Relationships
$race->circuit();          // BelongsTo
$race->results();          // HasMany RaceResult
$race->qualifyingResults();
$race->sessions();         // Practice, Quali, Sprint, Race
$race->news();             // Related articles
```

### Circuit
```php
$circuit = Circuit::create([
    'external_id' => 2,
    'name' => 'Bahrain International Circuit',
    'location' => 'Sakhir',
    'country' => 'Bahrain',
    'length' => 5.412,       // km
    'corners' => 15,
    'drs_zones' => 3,
    'lap_record' => '1:31.447',
    'lap_record_holder' => 'Pedro de la Rosa',
    'image' => 'https://...',
    'coordinates' => ['lat' => 26.0325, 'lng' => 50.5106],
]);
```

### News
```php
$news = News::create([
    'title' => 'Leclerc Takes Pole in Bahrain',
    'slug' => 'leclerc-takes-pole-in-bahrain-2025',
    'content' => 'Full article HTML...',
    'excerpt' => 'Short preview...',
    'summary' => 'AI-generated summary',
    'category' => 'qualifying|race|transfer|analysis|preview|technical',
    'status' => 'draft|pending_review|published',
    'ai_generated' => true,
    'published_at' => now(),
    'featured_image' => 'https://...',
    'seo_title' => '...',
    'seo_description' => '...',
]);

// Relationships
$news->drivers();          // MorphToMany
$news->teams();            // MorphToMany
$news->races();            // MorphToMany
$news->tags();             // MorphToMany
```

### Standing
```php
// Driver Championship
$standing = DriverStanding::create([
    'season' => 2025,
    'round' => 1,           // After which race
    'driver_id' => $driver->id,
    'position' => 1,
    'points' => 26,
    'wins' => 1,
]);

// Constructor Championship
$constructorStanding = ConstructorStanding::create([
    'season' => 2025,
    'round' => 1,
    'team_id' => $team->id,
    'position' => 1,
    'points' => 44,
    'wins' => 1,
]);
```

---

## 📰 News Generation Pipeline

### Pipeline Overview
```
┌─────────────┐   ┌─────────────┐   ┌─────────────┐   ┌─────────────┐
│   COLLECT   │ → │   ANALYZE   │ → │  GENERATE   │ → │   PUBLISH   │
└─────────────┘   └─────────────┘   └─────────────┘   └─────────────┘
      │                 │                 │                 │
  RSS Feeds         Dedupe          Select Topic      Quality Check
  API Data          Score           RAG Context       Auto-Publish
  Race Events       Rank            AI Generate       Social Share
```

### Jobs in the Pipeline

| Job | Purpose | Queue | Trigger |
|-----|---------|-------|---------|
| `FetchF1DataJob` | Sync data from F1 APIs | default | Hourly |
| `FetchNewsSourcesJob` | Fetch RSS from motorsport sites | default | Every 15 min |
| `ProcessRawContentJob` | Dedupe and score content | default | After fetch |
| `GenerateArticleIdeasJob` | Create ideas from race events | default | After race events |
| `EvaluateArticleIdeasJob` | Score and prioritize ideas | default | Hourly |
| `ResearchArticleJob` | Gather RAG context | default | Before write |
| `WriteArticleJob` | Generate article content | news-generation | When idea approved |
| `GenerateArticleImageJob` | Create featured images | images | After write |
| `LiveRaceUpdateJob` | Real-time race tracking | live | During races |
| `PostRaceAnalysisJob` | Generate race reports | news-generation | After race finish |

### Event-Driven Content Generation

| Event Type | Trigger | Article Type |
|------------|---------|--------------|
| Race Start | `race.status = 'live'` | Live Blog Start |
| Race Finish | `race.status = 'completed'` | Race Report |
| Qualifying | `qualifying.completed` | Qualifying Report |
| Driver Transfer | `driver.team_id changed` | Transfer News |
| Championship Math | Points calculation | Title Analysis |
| Fastest Lap | `race_result.fastest_lap` | Record News |
| DNF/Incident | `race_result.status = 'DNF'` | Incident Analysis |

### News Sources (Dynamic)

```bash
# Seed default F1 sources
php artisan sources:manage seed

# Supported sources
- Official F1 News (RSS)
- Autosport (RSS)
- Motorsport.com (RSS)
- The Race (RSS)
- PlanetF1 (RSS)
- RaceFans (RSS)
- OpenF1 API (Live data)
- Ergast API (Historical data)
```

---

## 🔄 Git Workflow

### Branch Structure
```
main                    # Production-ready code
├── develop             # Integration branch
│   ├── feature/*       # New features
│   ├── fix/*           # Bug fixes
│   └── improve/*       # Improvements
└── hotfix/*            # Production fixes
```

### Branch Naming
```bash
feature/live-race-tracker
feature/add-autosport-source
fix/standings-calculation
improve/race-result-rag
hotfix/api-timeout
```

### Commit Messages
```bash
# Format: <type>(<scope>): <subject>
feat(race): add live race tracking component
fix(standings): correct points calculation after sprint
perf(api): cache driver standings queries
docs(agents): update data model documentation
refactor(news): extract topic scoring to service
test(race): add race result integration tests
```

---

## ✅ Pre-Commit Checklist

```bash
# 1. Fix code style
vendor/bin/pint

# 2. Run tests
php artisan test

# 3. Check for errors
php artisan test --filter=F1

# 4. Verify migrations work
php artisan migrate:fresh --seed

# 5. Test the specific feature
php artisan f1:sync-data --test
```

---

## 🏛️ Code Architecture Standards

### Service Layer Pattern
```php
// ✅ Good - Business logic in services
class F1DataService
{
    public function syncDriverStandings(int $season): Collection
    {
        $data = $this->fetchFromAPI("/standings/drivers/{$season}");
        return $this->updateStandings($data);
    }
}

// ✅ Good - Jobs orchestrate services
class SyncStandingsJob implements ShouldQueue
{
    public function handle(F1DataService $service): void
    {
        $standings = $service->syncDriverStandings($this->season);
        event(new StandingsUpdated($standings));
    }
}

// ❌ Bad - Logic in controllers
class StandingsController
{
    public function sync()
    {
        // Don't put API logic here
    }
}
```

### RAG Context Building
```php
// Always provide rich context to AI for F1 articles
public function buildRAGContext(ContentIdea $idea): array
{
    return [
        'driver' => $this->getDriverContext($idea->driver),
        'team' => $this->getTeamContext($idea->team),
        'race' => $this->getRaceContext($idea->race),
        'circuit' => $this->getCircuitContext($idea->circuit),
        'standings' => $this->getCurrentStandings(),
        'season_stats' => $this->getSeasonStatistics($idea->driver),
        'head_to_head' => $this->getTeammateComparison($idea->driver),
        'recent_results' => $this->getRecentResults($idea->driver, 5),
        'historical_at_circuit' => $this->getHistoricalPerformance($idea->driver, $idea->circuit),
    ];
}
```

### AI Prompt Engineering
```php
// ✅ Good - Structured prompts with F1 context
$prompt = <<<PROMPT
You are a professional Formula 1 journalist writing for F1 Weekly.

TOPIC: {$topic}

CONTEXT:
- Driver: {$driver->name} ({$driver->team->name})
- Current Championship Position: {$standing->position} ({$standing->points} pts)
- Season Wins: {$seasonStats->wins}
- Recent Form: {$recentForm}
- Circuit: {$circuit->name} ({$circuit->country})
- Historical at this circuit: {$historicalStats}

REQUIREMENTS:
- Write 500-800 words
- Professional motorsport journalism style
- Include relevant statistics and lap times
- Reference specific race incidents when relevant
- Quote format when appropriate

OUTPUT: Return JSON with title, content, summary, tags.
PROMPT;

// ❌ Bad - Vague prompts
$prompt = "Write an article about {$driver->name}";
```

---

## 📁 File Organization

### When Creating New Features
1. **Model** → `app/Models/FeatureName.php`
2. **Service** → `app/Services/FeatureNameService.php`
3. **Job** → `app/Jobs/FeatureNameJob.php`
4. **Command** → `app/Console/Commands/FeatureCommand.php`
5. **Livewire** → `app/Livewire/FeatureName.php`
6. **Test** → `tests/Feature/FeatureNameTest.php`
7. **Migration** → `database/migrations/create_table.php`

### Naming Conventions
| Type | Convention | Example |
|------|------------|---------|
| Model | Singular, PascalCase | `Driver`, `RaceResult` |
| Service | DescriptiveService | `F1DataService` |
| Job | VerbNounJob | `SyncStandingsJob` |
| Command | prefix:action | `f1:sync-data` |
| Migration | snake_case | `create_race_results_table` |
| Route | dot.separated | `race.show`, `driver.results` |
| Livewire | PascalCase | `DriverStandings`, `LiveRaceTracker` |

---

## 🎨 Design System

### Philosophy
- **Racing Heritage** - Colors inspired by F1 (red, carbon black, championship gold)
- **Clean & Fast** - Minimal design that loads quickly
- **Data-Dense** - Display lots of info in scannable format
- **Dark Mode Ready** - Essential for night races

### Color Palette

| Token | Light | Dark | Purpose |
|-------|-------|------|---------|
| `--color-primary` | `#E10600` | `#FF1E00` | F1 Red - Primary actions |
| `--color-secondary` | `#15151E` | `#1F1F27` | Carbon - Headers, nav |
| `--color-accent-gold` | `#FFD700` | `#FFD700` | Championship gold |
| `--color-accent-silver` | `#C0C0C0` | `#C0C0C0` | Second place |
| `--color-accent-bronze` | `#CD7F32` | `#CD7F32` | Third place |
| `--color-live` | `#00FF00` | `#00FF00` | Live indicator |
| `--color-fastest` | `#A855F7` | `#A855F7` | Fastest lap purple |

### Team Colors (CSS Variables)
```css
--team-red-bull: #3671C6;
--team-ferrari: #E8002D;
--team-mercedes: #27F4D2;
--team-mclaren: #FF8000;
--team-aston-martin: #229971;
--team-alpine: #FF87BC;
--team-williams: #64C4FF;
--team-rb: #6692FF;
--team-kick-sauber: #52E252;
--team-haas: #B6BABD;
```

### Component Classes

#### Race Card
```html
<div class="race-card">
    <div class="race-card-header">
        <span class="race-round">Round 1</span>
        <span class="badge badge-live">LIVE</span>
    </div>
    <h3 class="race-card-title">Bahrain GP</h3>
    <p class="race-card-circuit">Bahrain International Circuit</p>
    <div class="race-card-date">March 2, 2025 • 15:00 UTC</div>
</div>
```

#### Driver Standings Row
```html
<div class="standings-row" style="--team-color: var(--team-ferrari)">
    <span class="position">1</span>
    <img class="driver-image" src="..." alt="Leclerc">
    <div class="driver-info">
        <span class="driver-name">Charles Leclerc</span>
        <span class="team-name">Ferrari</span>
    </div>
    <span class="points">26 pts</span>
</div>
```

---

## 🔍 SEO Requirements

### Every News Article Must Have
1. **SEO Title** - 50-60 characters, include driver/team name
2. **Meta Description** - 150-160 characters, include race name
3. **Canonical URL** - Prevent duplicates
4. **Open Graph** - For social sharing with race imagery
5. **Structured Data** - JSON-LD for SportsEvent, Article

```php
// Auto-generate SEO in AINewsGeneratorService
protected function generateSEO(News $news): void
{
    $news->update([
        'seo_title' => Str::limit($news->title, 60),
        'seo_description' => Str::limit(strip_tags($news->excerpt), 160),
    ]);
    
    // Generate structured data
    $news->structured_data = $this->generateArticleSchema($news);
}
```

---

## 📞 Useful Commands

```bash
# F1 Data Sync
php artisan f1:sync-data                    # Sync all current data
php artisan f1:sync-data --season=2025      # Specific season
php artisan f1:sync-standings               # Update standings
php artisan f1:fetch-race-results {race_id} # Fetch specific race

# News Generation
php artisan ai:generate-news --title="Topic"
php artisan news:generate-preview {race_id}  # Pre-race preview
php artisan news:generate-report {race_id}   # Post-race report

# Queue Management
php artisan queue:work --queue=default,news-generation,live,images
php artisan queue:failed
php artisan queue:retry all

# Testing
php artisan test --filter=F1
php artisan test tests/Feature/RaceResultTest.php

# Debugging
php artisan tinker
tail -f storage/logs/f1-news.log
```

---

## 📊 Key Metrics to Monitor

| Metric | Target | Alert Threshold |
|--------|--------|-----------------|
| Articles/race weekend | 10-15 | <5 |
| Data sync latency | <5 min | >15 min |
| Quality score | >85 | <70 |
| Live update latency | <30 sec | >2 min |
| Page load (race day) | <2s | >4s |

---

## 🚨 Common Pitfalls to Avoid

### F1 Data
1. **Timezone handling** - All race times in UTC, convert for display
2. **Sprint format** - Different points system, separate standings
3. **Shared drivers** - Reserve/test drivers may have multiple teams
4. **Season transitions** - Handle pre-season testing data carefully

### News Generation
1. **Don't skip RAG context** - Always gather driver/race data
2. **Verify statistics** - Cross-check with database before publishing
3. **Handle race incidents** - Be factual, avoid speculation
4. **Respect embargoes** - Some news has release timing requirements

### Live Race Data
1. **Connection resilience** - Handle API disconnections gracefully
2. **Data validation** - Lap times can have errors, validate ranges
3. **Update batching** - Don't spam database during live race
4. **Cache strategy** - Invalidate correctly after race status changes

---

## 📝 When Stuck

1. Check existing similar implementations in codebase
2. Review the F1 data models and relationships
3. Check logs: `storage/logs/f1-news.log`
4. Test in isolation: `php artisan tinker`
5. Validate API responses: `php artisan f1:test-api`
6. Follow principle: "When in doubt, add more logging"

---

**Remember:** The goal is autonomous, high-quality Formula 1 journalism. Every article should read like it was written by a professional motorsport journalist with access to comprehensive telemetry and statistics.
