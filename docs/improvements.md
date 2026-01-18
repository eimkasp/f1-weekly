# F1 Weekly - Improvement Roadmap (Updated Jan 2026)

This document outlines the current feature requests and improvement plans for the F1 Weekly application, prioritizing engagement and user experience.

## 🔴 Must Have
*Critical features necessary for the core value proposition and operations.*

1.  **Quiz Administration (Filament)**
    *   **Context:** We launched the Quiz UI, but currently, questions can only be added via Database Seeder.
    *   **Task:** Create a `QuestionResource` in Filament.
    *   **Fields:** Question Text, Type (Trivia/Survival), Difficulty, Category, Options (Repeater), Correct Answer, Explanation.
2.  **Team/Constructor Profiles**
    *   **Context:** Users can click on Drivers, but Team names are currently just text or unlinked.
    *   **Task:** Create `Team` model, migration, and `teams.show` view with team history, car specs, and driver lineup.
3.  **Global Search**
    *   **Context:** No way to search for specific content.
    *   **Task:** Implement full-site search (News, Drivers, Races, Trivia) in the top navigation bar.
4.  **Data Automation Stability**
    *   **Context:** Current data fetching interacts with external sources.
    *   **Task:** Harden the `LiveRaceTracker` and `NewsFetcher` services with better error handling, rate limiting, and fallback data sources.

## 🟡 Should Have
*Important features that provide significant value but aren't vital for the app to function.*

1.  **User Identity System**
    *   **Context:** Quizzes are currently stateless per session.
    *   **Task:** Implement `auth` (Breeze/Jetstream).
    *   **Value:** Enable saving "Survival Mode" high scores and "Favorite Driver" preferences to personalize the feed.
2.  **Newsletter Integration**
    *   **Context:** UI Element exists in the bottom sheet.
    *   **Task:** Connect to a mail provider (e.g., Resend/Mailchimp) to collect emails and send weekly summaries.
3.  **Interactive Circuit Maps**
    *   **Context:** Calendar just lists text location, map is static.
    *   **Task:** Add SVG/Canvas maps for each circuit showing layout, DRS zones, and sector colors.
4.  **Dark/Light Mode Toggle**
    *   **Context:** Currently respects system settings only.
    *   **Task:** Add a manual toggle in the UI for user preference.

## 🟢 Could Have
*Desirable features that add delight but can be delayed.*

1.  **Leaderboards**
    *   **Task:** Global high-score tables for the "Survival Mode" quiz. Daily/Weekly resets.
2.  **Social Sharing Images (Dynamic OG)**
    *   **Task:** Generate social images on the fly: "I scored 15 in F1 Survival Mode! 🏎️💨".
3.  **Polls & Predictions**
    *   **Task:** "Who will win the GP?" poll widget on the Homepage before race weekends.
4.  **Comments Section**
    *   **Task:** Allow authenticated users to discuss News articles.

## ⚪ Won't Have (For Now)
*Ideas that are out of scope for the current phase.*

1.  **Live Video Streaming** (Licensing issues).
2.  **Native Mobile Apps** (PWA focus is sufficient).
3.  **Ticket Marketplace** (Liability/Complexity).
