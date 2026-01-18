# F1 Weekly API - cURL Commands Reference

This document provides all curl commands to interact with the F1 Weekly API.

## Base URL
```
http://localhost:8001/api
```

## Health Check
```bash
curl http://localhost:8001/api/health
```

---

## 📊 Data Synchronization

### Sync All F1 Data
```bash
# Sync all data for current season
curl -X POST http://localhost:8001/api/sync/all

# Sync all data for specific season
curl -X POST "http://localhost:8001/api/sync/all?season=2024"

# Force sync (bypass cache)
curl -X POST "http://localhost:8001/api/sync/all?force=true"
```

### Sync Specific Data Types
```bash
# Sync teams
curl -X POST "http://localhost:8001/api/sync/teams?season=2024"

# Sync drivers
curl -X POST "http://localhost:8001/api/sync/drivers?season=2024"

# Sync circuits
curl -X POST http://localhost:8001/api/sync/circuits

# Sync race calendar
curl -X POST "http://localhost:8001/api/sync/races?season=2024"

# Sync championship standings
curl -X POST "http://localhost:8001/api/sync/standings?season=2024"

# Sync race results for specific race
curl -X POST http://localhost:8001/api/sync/results/2024/1
```

---

## 🏆 Standings

### Driver Standings
```bash
# Get current driver standings
curl http://localhost:8001/api/standings/drivers

# Get driver standings for specific season
curl http://localhost:8001/api/standings/drivers/2023
```

### Constructor Standings
```bash
# Get current constructor standings
curl http://localhost:8001/api/standings/constructors

# Get constructor standings for specific season
curl http://localhost:8001/api/standings/constructors/2023
```

---

## 🏎️ Races

```bash
# List all races (current season)
curl http://localhost:8001/api/races

# List races for specific season
curl http://localhost:8001/api/races/2024

# Get next upcoming race
curl http://localhost:8001/api/races/next

# Get last completed race
curl http://localhost:8001/api/races/last

# Get specific race details
curl http://localhost:8001/api/races/2024/1
```

---

## 👨‍✈️ Drivers & Teams

```bash
# List all active drivers
curl http://localhost:8001/api/drivers

# Get specific driver
curl http://localhost:8001/api/drivers/1

# List all active teams
curl http://localhost:8001/api/teams

# Get specific team
curl http://localhost:8001/api/teams/1
```

---

## 📰 Article Generation

### Generate Article from Title
```bash
# Basic article generation
curl -X POST http://localhost:8001/api/articles/generate \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Max Verstappen dominates Monaco GP qualifying",
    "category": "race"
  }'

# Full article generation with all options
curl -X POST http://localhost:8001/api/articles/generate \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Ferrari brings major upgrades for British GP",
    "category": "team",
    "description": "Ferrari introduces significant aero upgrades for Silverstone",
    "team_id": 3,
    "priority": "high"
  }'

# Categories: race, driver, team, technical, preview, opinion
```

### Generate from Content Idea
```bash
# Generate article from existing content idea
curl -X POST http://localhost:8001/api/articles/generate-from-idea/1
```

### Suggest Content Topics
```bash
# Get 5 random topic suggestions
curl -X POST http://localhost:8001/api/articles/suggest-topics \
  -H "Content-Type: application/json" \
  -d '{"count": 5}'

# Get specific type suggestions
curl -X POST http://localhost:8001/api/articles/suggest-topics \
  -H "Content-Type: application/json" \
  -d '{"count": 3, "type": "driver"}'
```

### List & Manage Articles
```bash
# List all articles
curl http://localhost:8001/api/articles

# Filter by status
curl "http://localhost:8001/api/articles?status=published"

# Filter by category
curl "http://localhost:8001/api/articles?category=race"

# Only AI-generated
curl "http://localhost:8001/api/articles?ai_only=true"

# Get single article
curl http://localhost:8001/api/articles/1

# Update article status (publish)
curl -X PATCH http://localhost:8001/api/articles/1/status \
  -H "Content-Type: application/json" \
  -d '{"status": "published"}'

# Statuses: draft, pending_review, published, archived
```

---

## 💡 Content Ideas

### List & Create Ideas
```bash
# List all content ideas
curl http://localhost:8001/api/ideas

# Filter by status
curl "http://localhost:8001/api/ideas?status=draft"

# Create new content idea
curl -X POST http://localhost:8001/api/ideas \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Hamilton reflects on Mercedes journey",
    "type": "driver",
    "description": "Interview analysis and career retrospective",
    "driver_id": 4,
    "priority": "high"
  }'

# Get single idea
curl http://localhost:8001/api/ideas/1
```

### Approve/Reject Ideas
```bash
# Approve idea for generation
curl -X PATCH http://localhost:8001/api/ideas/1/approve

# Reject idea
curl -X PATCH http://localhost:8001/api/ideas/1/reject
```

---

## 🔄 Full Workflow Example

### 1. Sync Latest Data
```bash
# Sync teams, drivers, and standings
curl -X POST "http://localhost:8001/api/sync/all?force=true"
```

### 2. Check Current Standings
```bash
curl http://localhost:8001/api/standings/drivers
```

### 3. Create Content Idea
```bash
curl -X POST http://localhost:8001/api/ideas \
  -H "Content-Type: application/json" \
  -d '{"title": "Championship battle heats up", "type": "analysis"}'
```

### 4. Approve & Generate Article
```bash
# Approve the idea
curl -X PATCH http://localhost:8001/api/ideas/1/approve

# Generate the article
curl -X POST http://localhost:8001/api/articles/generate-from-idea/1
```

### 5. Publish Article
```bash
curl -X PATCH http://localhost:8001/api/articles/1/status \
  -H "Content-Type: application/json" \
  -d '{"status": "published"}'
```

---

## ⚙️ Artisan Commands (Alternative)

If you prefer CLI commands:

```bash
# Sync F1 data
php artisan f1:sync-data --type=all --season=2024 --force

# Generate AI news
php artisan ai:generate-news --type=race --limit=3

# Suggest content topics
php artisan ai:suggest-topics --count=5
```

---

## 📝 Notes

- **OpenAI API Key Required**: Article generation requires a valid `OPENAI_API_KEY` in `.env`
- **Rate Limiting**: The Ergast API has rate limits. Use `force=false` in production.
- **Caching**: Data is cached (30min for standings, 1hr for races, 24hr for circuits)
- **Authentication**: These endpoints are currently open. Add `auth:sanctum` middleware for production.
